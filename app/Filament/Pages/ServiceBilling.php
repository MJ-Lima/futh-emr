<?php

namespace App\Filament\Pages;

use App\Models\Patient;
use App\Models\Service;
use App\Services\ServiceBillingService;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Schemas\Schema;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Illuminate\Support\Facades\Auth;

/**
 * "Bill a Service" desk.
 *
 * The authorised users for each service are configured on the service
 * itself (allowed_roles, editable via Billing & Finance â†’ Services).
 * Adding a service here creates an invoice, which automatically appears
 * at the cashier point (Cashier / Payments â†’ Cashier Tasks) for payment
 * collection.
 */
class ServiceBilling extends Page implements HasForms
{
    use InteractsWithForms;

    protected string $view = 'filament.pages.service-billing';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-document-currency-dollar';

    protected static string|\UnitEnum|null $navigationGroup = 'Front Desk';

    protected static ?string $navigationLabel = 'Bill a Service';

    protected static ?string $title = 'Bill a Service â€” Point of Service Billing';

    protected static ?int $navigationSort = 4;

    public ?array $data = [];

    public static function canAccess(): bool
    {
        $user = auth()->user();
        if (! $user) {
            return false;
        }

        // Desk is open to billing-capable roles; the individual services
        // each user may bill are filtered by services.allowed_roles.
        return $user->hasRole([
            'super_admin', 'medical_administrator', 'accountant', 'biller',
            'cashier', 'medical_records_officer', 'insurance_officer',
            'head_of_records',
        ]);
    }

    public function mount(): void
    {
        $this->form->fill([
            'bill_to'          => 'patient',
            'quantity'         => 1,
            'payment_mode'     => 'Cash',
            'insurance_covered' => 0,
        ]);
    }

    public function form(Schema $form): Schema
    {
        return $form
            ->schema([
                Section::make('Who is this bill for?')
                    ->description('Registered patients are billed to their hospital record. Walk-in customers (gas plant buyers, interns, research students, card/form applicants) do not need a patient record.')
                    ->schema([
                        Radio::make('bill_to')
                            ->label('Bill to')
                            ->options([
                                'patient' => 'Registered patient',
                                'walk_in' => 'Walk-in customer (gas plant, internship, research, forms, ID cards)',
                            ])
                            ->default('patient')
                            ->live()
                            ->columnSpanFull(),

                        Grid::make(2)->schema([
                            Select::make('patient_id')
                                ->label('Patient')
                                ->hint('Search by hospital number, name or phone')
                                ->searchable()
                                ->getSearchResultsUsing(fn (string $search): array => $this->searchPatients($search))
                                ->getOptionLabelUsing(function ($value): string {
                                    $p = Patient::find($value);

                                    return $p ? "{$p->hospital_no} â€” {$p->full_name}" : '';
                                })
                                ->visible(fn ($get) => $get('bill_to') === 'patient')
                                ->required(fn ($get) => $get('bill_to') === 'patient')
                                ->live()
                                ->afterStateUpdated(function ($state, $set) {
                                    if (! $state) {
                                        return;
                                    }
                                    $patient = Patient::find($state);
                                    if (! $patient) {
                                        return;
                                    }
                                    // Default the payment mode to the patient's scheme.
                                    $set('payment_mode', match ($patient->patient_type) {
                                        'nhia'            => 'NHIA',
                                        'nashia'          => 'NASHIA',
                                        'corporate'       => 'Corporate',
                                        'retainership'    => 'Insurance',
                                        'other_insurance' => 'Insurance',
                                        default           => 'Cash',
                                    });
                                }),

                            TextInput::make('customer_name')
                                ->label('Walk-in customer name')
                                ->visible(fn ($get) => $get('bill_to') === 'walk_in')
                                ->required(fn ($get) => $get('bill_to') === 'walk_in')
                                ->maxLength(150),
                        ]),

                        TextInput::make('customer_phone')
                            ->label('Walk-in customer phone (optional)')
                            ->tel()
                            ->visible(fn ($get) => $get('bill_to') === 'walk_in')
                            ->maxLength(30),
                    ]),

                Section::make('Service')
                    ->description('Only services you are permitted to bill are listed. Walk-in bills show walk-in enabled services only (gas plant, internship, research, forms, ID cards).')
                    ->schema([
                        Select::make('service_id')
                            ->label('Service')
                            ->hint('Search by name, code or NHIA code')
                            ->searchable()
                            ->getSearchResultsUsing(fn (string $search): array => $this->searchServices($search))
                            ->getOptionLabelUsing(function ($value): string {
                                $s = Service::find($value);

                                return $s ? "{$s->name} ({$s->code})" : '';
                            })
                            ->required()
                            ->live()
                            ->afterStateUpdated(fn ($state, $set, $get) => $this->resolveUnitPrice($set, (string) $get('payment_mode'), $state)),

                        Grid::make(3)->schema([
                            Select::make('payment_mode')
                                ->label('Payment mode')
                                ->options([
                                    'Cash'      => 'Cash',
                                    'NHIA'      => 'NHIA (NHIS â€” federal)',
                                    'NASHIA'    => 'NASHIA (Nasarawa State)',
                                    'Insurance' => 'Insurance / HMO',
                                    'Corporate' => 'Corporate',
                                    'Charity'   => 'Charity',
                                    'Mixed'     => 'Mixed',
                                ])
                                ->default('Cash')
                                ->live()
                                ->afterStateUpdated(fn ($state, $set, $get) => $this->resolveUnitPrice($set, (string) $state, $get('service_id'))),

                            TextInput::make('quantity')
                                ->label('Quantity')
                                ->numeric()->minValue(1)->default(1)
                                ->required(),

                            TextInput::make('unit_price')
                                ->label('Unit price (â‚¦)')
                                ->numeric()->minValue(0)
                                ->required()
                                ->hint('Auto-filled from the tariff â€” editable'),
                        ]),

                        TextInput::make('insurance_covered')
                            ->label('Amount covered by insurance (â‚¦)')
                            ->numeric()->minValue(0)->default(0)
                            ->visible(fn ($get) => in_array($get('payment_mode'), ['NHIA', 'NASHIA', 'Insurance', 'Corporate']))
                            ->helperText('What the scheme/HMO covers. The cashier collects the remaining patient portion.'),

                        Textarea::make('notes')
                            ->label('Notes (optional)')
                            ->rows(2)
                            ->columnSpanFull(),
                    ]),
            ])
            ->statePath('data');
    }

    /**
     * Create the invoice. The cashier task is auto-created by
     * CashierTaskService (Invoice::created listener) and appears at the
     * cashier point for payment collection.
     */
    public function bill(): void
    {
        $data = $this->form->getState();

        // Guard against stale values when the user toggled between
        // patient and walk-in mode after filling the other side.
        if (($data['bill_to'] ?? 'patient') === 'walk_in') {
            $data['patient_id'] = null;
        } else {
            $data['customer_name'] = null;
            $data['customer_phone'] = null;
        }

        // Manual guards beyond field rules (walk-in vs patient).
        if (($data['bill_to'] ?? 'patient') === 'patient' && empty($data['patient_id'])) {
            Notification::make()->title('Select a patient first')->danger()->send();
            return;
        }
        if (($data['bill_to'] ?? '') === 'walk_in' && empty($data['customer_name'])) {
            Notification::make()->title('Enter the walk-in customer name')->danger()->send();
            return;
        }

        try {
            $invoice = app(ServiceBillingService::class)->createBill($data);
        } catch (\InvalidArgumentException $e) {
            Notification::make()->title('Cannot bill this service')->body($e->getMessage())->danger()->send();
            return;
        } catch (\Throwable $e) {
            report($e);
            Notification::make()->title('Something went wrong')->body('The bill could not be created. Please try again.')->danger()->send();
            return;
        }

        Notification::make()
            ->title('Invoice created â€” sent to cashier point')
            ->body(
                'Invoice ' . $invoice->invoice_number . ' for â‚¦' . number_format((float) $invoice->patient_portion, 2)
                . ' (' . $invoice->billed_to_label . '). The cashier can now collect the payment.'
            )
            ->success()
            ->send();

        $this->form->fill([
            'bill_to'           => 'patient',
            'quantity'          => 1,
            'payment_mode'      => 'Cash',
            'insurance_covered' => 0,
        ]);
    }

    /* -----------------------------------------------------------------
     |  Helpers
     | ----------------------------------------------------------------- */

    protected function searchPatients(string $search): array
    {
        if (mb_strlen(trim($search)) < 2) {
            return [];
        }

        return Patient::query()
            ->where('hospital_no', 'like', "%{$search}%")
            ->orWhere('firstname', 'like', "%{$search}%")
            ->orWhere('lastname', 'like', "%{$search}%")
            ->orWhere('phonemain', 'like', "%{$search}%")
            ->orderBy('lastname')
            ->limit(50)
            ->get()
            ->mapWithKeys(fn (Patient $p) => [$p->id => "{$p->hospital_no} â€” {$p->full_name}"])
            ->all();
    }

    protected function searchServices(string $search): array
    {
        if (mb_strlen(trim($search)) < 2) {
            return [];
        }

        $user  = Auth::user();
        $roles = $user?->roles()->pluck('name')->all() ?? [];
        $walkIn = ($this->data['bill_to'] ?? 'patient') === 'walk_in';

        $services = Service::query()
            ->where('status', 'Active')
            ->when($walkIn, fn ($q) => $q->where('allows_walk_in', true))
            ->when(
                ! $user?->hasRole('super_admin'),
                fn ($q) => $q->billableBy($roles)
            )
            ->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('nhia_code', 'like', "%{$search}%");
            })
            ->with('serviceCategory')
            ->orderBy('name')
            ->limit(100)
            ->get();

        return $services
            ->mapWithKeys(function (Service $s) {
                $category = $s->serviceCategory?->name ?? ucfirst((string) $s->category);
                $price    = 'â‚¦' . number_format((float) ($s->standard_price ?: $s->nhia_rate), 2);

                return [$s->id => "[{$category}] {$s->name} â€” {$price}"];
            })
            ->all();
    }

    protected function resolveUnitPrice($set, ?string $paymentMode, $serviceId): void
    {
        if (! $serviceId) {
            return;
        }
        $service = Service::find($serviceId);
        if (! $service) {
            return;
        }

        $set('unit_price', $service->priceFor(Service::schemeForPaymentMode($paymentMode ?? 'Cash')));
    }
}
