<?php

namespace App\Filament;

use Filament\Resources\Resource;

class BaseResource extends Resource
{
    public static function canViewAny(): bool
    {
        if (auth()->user()?->hasRole('super_admin')) {
            return true;
        }

        $modelClass = static::getModel();

        if (!class_exists($modelClass)) {
            return false;
        }

        $shortName = (new \ReflectionClass($modelClass))->getShortName();
        $resourceName = lcfirst($shortName);

        return auth()->user()?->can("view {$resourceName}") ?? false;
    }
}