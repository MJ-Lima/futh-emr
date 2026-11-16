<?php

namespace App\Filament\Resources\Patients\Schemas;


use Filament\Schemas\Schema;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use App\Models\State;
use App\Models\Local;

class PatientForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                // ---- Section 1: Patient Type ----
                Section::make('1. Patient Type')
                    ->schema([
                        Radio::make('patient_type')
                            ->label('')
                            ->options([
                                'private' => 'Private (Self Pay)',
                                'nhia' => 'NHIA',
                                'nashia' => 'NASHIA',
                                'retainership' => 'Retainership',
                                'corporate' => 'Corporate',
                                'other_insurance' => 'Other Insurance',
                            ])
                            ->default('private')
                            ->columns(3)
                            ->descriptions([
                                'private' => 'Patient pays out of pocket',
                                'nhia' => 'National Health Insurance Authority',
                                'nashia' => 'Nasarawa State Health Insurance',
                            ]),
                    ])
                    ->collapsible(),

                // ---- Section 2: Personal Information ----
                Section::make('2. Personal Information')
                    ->schema([
                        Grid::make(4)->schema([
                            // Photo upload spanning 1 row
                            FileUpload::make('photo_path')
                                ->label('Profile Photo')
                                ->image()
                                ->imagePreviewHeight('120')
                                ->directory('patients/photos')
                                ->disk('public')
                                ->visibility('public')
                                ->columnSpan(1)
                                ->helperText('Allowed JPG, PNG or GIF (max. 2MB)'),

                            // Read-only auto-generated fields
                            TextInput::make('hospital_no')
                                ->label('Hospital No')
                                ->disabled()
                                ->dehydrated(false)
                                ->placeholder('Auto-generated'),

                            TextInput::make('upi')
                                ->label('UPI')
                                ->disabled()
                                ->dehydrated(false)
                                ->placeholder('Auto-generated (if insured)'),

                            TextInput::make('nin')
                                ->label('NIN')
                                ->maxLength(11)
                                ->placeholder('National ID Number'),

                            Select::make('title')
                                ->label('Title')
                                ->options([
                                    'Mr' => 'Mr',
                                    'Mrs' => 'Mrs',
                                    'Miss' => 'Miss',
                                    'Ms' => 'Ms',
                                    'Dr' => 'Dr',
                                    'Prof' => 'Prof',
                                    'Chief' => 'Chief',
                                    'Alhaji' => 'Alhaji',
                                    'Hajia' => 'Hajia',
                                ]),

                            TextInput::make('lastname')
                                ->label('Surname')
                                ->required(),

                            TextInput::make('firstname')
                                ->label('First Name')
                                ->required(),

                            TextInput::make('middlename')
                                ->label('Other Name'),

                            Select::make('gender')
                                ->label('Gender')
                                ->options([
                                    'male' => 'Male',
                                    'female' => 'Female',
                                ])
                                ->required(),

                            DatePicker::make('dob')
                                ->label('Date of Birth')
                                ->required()
                                ->native(false)
                                ->displayFormat('d-m-Y'),

                            TextInput::make('age')
                                ->label('Age')
                                ->disabled()
                                ->dehydrated(false)
                                ->placeholder('Auto-calculated from DOB'),

                            Select::make('marital')
                                ->label('Marital Status')
                                ->options([
                                    'single' => 'Single',
                                    'married' => 'Married',
                                    'divorced' => 'Divorced',
                                    'widowed' => 'Widowed',
                                ])
                                ->required(),

                            TextInput::make('occupation')
                                ->label('Occupation'),

                            Select::make('genotype')
                                ->label('Genotype')
                                ->options([
                                    'AA' => 'AA',
                                    'AS' => 'AS',
                                    'AC' => 'AC',
                                    'SS' => 'SS',
                                    'SC' => 'SC',
                                    'CC' => 'CC',
                                ]),

                            Select::make('bloodgroup')
                                ->label('Blood Group')
                                ->options([
                                    'A+' => 'A+',
                                    'A-' => 'A-',
                                    'B+' => 'B+',
                                    'B-' => 'B-',
                                    'AB+' => 'AB+',
                                    'AB-' => 'AB-',
                                    'O+' => 'O+',
                                    'O-' => 'O-',
                                ]),

                            Select::make('religion')
                                ->label('Religion')
                                ->options([
                                    'Christianity' => 'Christianity',
                                    'Islam' => 'Islam',
                                    'Traditional' => 'Traditional',
                                ]),

                            TextInput::make('phonemain')
                                ->label('Phone Number')
                                ->tel()
                                ->prefix('+234')
                                ->required(),
                        ]),
                    ])
                    ->collapsible(),

                // ---- Section 3: Contact Information ----
                Section::make('3. Contact Information')
                    ->schema([
                        Grid::make(4)->schema([
                            Select::make('country')
                                ->label('Nationality')
                                ->options(['Nigeria' => 'Nigeria'])
                                ->default('Nigeria')
                                ->required(),

                    Select::make('state')
                    ->label('State')
                        ->options(fn () => State::pluck('name', 'name')->toArray())
                             ->searchable()
                             ->live()
                            ->afterStateUpdated(fn ($state, $set) => $set('lga', null)),

                    Select::make('lga')
                             ->label('LGA')
                            ->options(function (callable $get) {
                                $stateName = $get('state');
                                if (!$stateName) return [];
                                $state = State::where('name', $stateName)->first();
                                if (!$state) return [];
                        // Strip 'NG-' prefix from state code (states.code='NG-AB', lgas.STATE_CODE='AB')
                                $stateCode = str_replace('NG-', '', $state->code);
                                    return \DB::table('lgas')
                            ->where('STATE_CODE', $stateCode)
                            ->pluck('NAME', 'NAME')
                            ->toArray();
                                    })
                        ->searchable(),

                            TextInput::make('city')
                                ->label('City')
                                ->required(),

                            TextInput::make('postalcode')
                                ->label('Postal Code'),

                            TextInput::make('email')
                                ->label('Email Address')
                                ->email(),

                            Textarea::make('address')
                                ->label('Residential Address')
                                ->rows(2)
                                ->columnSpanFull(),
                        ]),
                    ])
                    ->collapsible(),

                // ---- Section 4: Biometrics Identification ----
                Section::make('4. Biometrics Identification')
                    ->description('Capture thumbprint for patient identity verification')
                    ->schema([
                        Grid::make(2)->schema([
                            FileUpload::make('thumbprint_path')
                                ->label('Thumbprint Capture')
                                ->image()
                                ->imagePreviewHeight('200')
                                ->directory('patients/thumbprints')
                                ->disk('public')
                                ->visibility('public')
                                ->acceptedFileTypes(['image/png', 'image/jpeg', 'image/bmp'])
                                ->helperText('Click to capture or upload thumbprint image')
                                ->columnSpan(1),

                            Section::make('Capture Status')
                                ->schema([
                                    TextInput::make('verification_status')
                                        ->label('Verification Status')
                                        ->disabled()
                                        ->default('Pending')
                                        ->dehydrated(false),
                                    TextInput::make('capture_quality')
                                        ->label('Capture Quality')
                                        ->disabled()
                                        ->default('—')
                                        ->dehydrated(false),
                                ])
                                ->columnSpan(1),
                        ]),
                    ])
                    ->collapsible(),

                // ---- Section 5: Next of Kin ----
                Section::make('5. Next of Kin')
                    ->schema([
                        Grid::make(4)->schema([
                            TextInput::make('nokname')
                                ->label('Surname')
                                ->required(),

                            TextInput::make('nok_firstname')
                                ->label('First Name'),

                            TextInput::make('nok_middlename')
                                ->label('Other Name'),

                            Select::make('relationship')
                                ->label('Relationship')
                                ->options([
                                    'Spouse' => 'Spouse',
                                    'Mother' => 'Mother',
                                    'Father' => 'Father',
                                    'Son' => 'Son',
                                    'Daughter' => 'Daughter',
                                    'Brother' => 'Brother',
                                    'Sister' => 'Sister',
                                    'Guardian' => 'Guardian',
                                    'Other' => 'Other',
                                ])
                                ->required(),

                            TextInput::make('nokphone')
                                ->label('Phone Number')
                                ->tel()
                                ->prefix('+234')
                                ->required(),

                            TextInput::make('nok_email')
                                ->label('Email Address')
                                ->email(),

                            Textarea::make('nokaddress')
                                ->label('Residential Address')
                                ->rows(2)
                                ->columnSpanFull(),
                        ]),
                    ])
                    ->collapsible(),
            ]);
    }
}
