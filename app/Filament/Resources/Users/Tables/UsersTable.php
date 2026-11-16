<?php

namespace App\Filament\Resources\Users\Tables;

use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\RecordActionsPosition;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Spatie\Permission\Models\Role;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('Name')->searchable()->weight('semibold')
                     ->description(fn ($r) => $r?->email ?? '—'),
                TextColumn::make('roles.name')
                    ->label('Roles')
                    ->badge()
                    ->color('info')
                    ->placeholder('No role'),
                TextColumn::make('hospital.name')->label('Hospital')->placeholder('—')->toggleable(),
                TextColumn::make('department.name')->label('Department')->placeholder('—')->toggleable(),
                TextColumn::make('phone')->label('Phone')->placeholder('—')->toggleable(),
                TextColumn::make('status')->label('Status')->badge()
                    ->color(fn ($s) => match ($s) {
                        'active'    => 'success',
                        'inactive'  => 'gray',
                        'suspended' => 'warning',
                        'locked'    => 'danger',
                        default      => 'gray',
                    }),
                TextColumn::make('last_login_at')->label('Last Login')->dateTime('d M Y, H:i')->placeholder('Never')->toggleable(),
            ])
            ->filters([
                SelectFilter::make('roles')
                    ->label('Role')
                    ->relationship('roles', 'name')
                    ->multiple()
                    ->preload(),
                SelectFilter::make('status')
                    ->options([
                        'active'    => 'Active',
                        'inactive'  => 'Inactive',
                        'suspended' => 'Suspended',
                        'locked'    => 'Locked',
                    ]),
            ])
            ->recordActions([
                ViewAction::make()->visible(true),
                EditAction::make()->visible(true)->label('Edit'),
            ], position: RecordActionsPosition::BeforeColumns)
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
