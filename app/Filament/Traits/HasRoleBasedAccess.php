<?php

namespace App\Filament\Traits;

trait HasRoleBasedAccess
{
    public static function canViewAny(): bool
    {
        $modelName = str_replace('Resource', '', class_basename(static::class));
        return auth()->user()?->can("ViewAny:{$modelName}") ?? false;
    }

    public static function canCreate(): bool
    {
        $modelName = str_replace('Resource', '', class_basename(static::class));
        return auth()->user()?->can("Create:{$modelName}") ?? false;
    }

    public static function canEdit(\Illuminate\Database\Eloquent\Model $record): bool
    {
        $modelName = str_replace('Resource', '', class_basename(static::class));
        return auth()->user()?->can("Update:{$modelName}") ?? false;
    }

    public static function canDelete(\Illuminate\Database\Eloquent\Model $record): bool
    {
        $modelName = str_replace('Resource', '', class_basename(static::class));
        return auth()->user()?->can("Delete:{$modelName}") ?? false;
    }
}