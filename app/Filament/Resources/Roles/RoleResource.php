<?php

namespace App\Filament\Resources\Roles;

use App\Filament\Traits\HasRoleBasedAccess;
use App\Filament\Resources\Roles\Pages\{CreateRole, EditRole, ListRoles};
use App\Filament\Traits\HasUnitScoping;
use BackedEnum;
use Filament\Actions\{BulkActionGroup, DeleteBulkAction, EditAction};
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Spatie\Permission\Models\Role;

class RoleResource extends Resource
{
    use HasRoleBasedAccess;

    public static function getNavigationIcon(): Heroicon
    {
        return Heroicon::OutlinedShieldCheck;
    }
    protected static ?string $model = Role::class;

public static function canAccess(): bool
{
    return auth()->user()?->hasRole('super_admin') ?? false;
}
    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),
                TextInput::make('guard_name')
                    ->default('web')
                    ->required()
                    ->maxLength(255),
                
                CheckboxList::make('permissions')
                    ->label('Permissions')
                    ->options(function () {
                        return \Spatie\Permission\Models\Permission::orderBy('name')
                            ->pluck('name', 'name')
                            ->toArray();
                    })
                    ->searchable()
                    ->columns(3)
                    ->gridDirection('row')
                    ->columnSpanFull()
                    ->dehydrated(false) // Don't let Filament auto-save this relationship
                    ->afterStateHydrated(function (CheckboxList $component, $state, $record) {
                        // When editing, populate with the role's current permission names
                        if ($record) {
                            $component->state($record->permissions->pluck('name')->toArray());
                        }
                    })
            ->helperText('Changes are saved via Spatie\'s syncPermissions() - the cache is cleared automatically.'),            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('guard_name')
                    ->sortable(),
                TextColumn::make('permissions_count')
                    ->counts('permissions')
                    ->label('Permissions')
                    ->badge()
                    ->color('success'),
                TextColumn::make('users_count')
                    ->counts('users')
                    ->label('Users')
                    ->badge()
                    ->color('info'),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->actions([
                EditAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListRoles::route('/'),
            'create' => CreateRole::route('/create'),
            'edit'   => EditRole::route('/{record}/edit'),
        ];
    }
}