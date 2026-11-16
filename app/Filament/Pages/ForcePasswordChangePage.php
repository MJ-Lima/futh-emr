<?php

namespace App\Filament\Pages;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ForcePasswordChangePage extends Page
{
    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-key';
    protected  string $view = 'filament.pages.force-password-change';

    protected static ?string $title = 'Change Your Password';

    protected static bool $shouldRegisterNavigation = false;

    public ?array $data = [];

    public function mount(): void
    {
        if (! auth()->check()) {
            abort(403);
        }

        if (! auth()->user()->must_change_password) {
            redirect()->route('filament.admin.pages.dashboard');
        }
    }

    public function form(Schema $form): Schema
    {
        return $form
            ->schema([
                TextInput::make('current_password')
                    ->label('Current password')
                    ->password()
                    ->required()
                    ->rules(['current_password']),
                TextInput::make('new_password')
                    ->label('New password')
                    ->password()
                    ->required()
                    ->rule(Password::min(8)->mixedCase()->numbers()),
                TextInput::make('new_password_confirmation')
                    ->label('Confirm new password')
                    ->password()
                    ->required()
                    ->same('new_password'),
            ])
            ->statePath('data');
    }

    public function changePassword(): void
    {
        $data = $this->form->getState();

        $user = auth()->user();

        // Verify current password
        if (! Hash::check($data['current_password'], $user->password)) {
            Notification::make()
                ->title('Current password is incorrect')
                ->danger()
                ->send();
            return;
        }

        // Change the password and clear the flag
        $user->changePassword($data['new_password']);

        Notification::make()
            ->title('Password changed successfully')
            ->body('You can now continue using the system.')
            ->success()
            ->send();

        redirect()->route('filament.admin.pages.dashboard');
    }
}
