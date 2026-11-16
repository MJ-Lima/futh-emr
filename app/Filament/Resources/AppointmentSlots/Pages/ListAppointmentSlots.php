<?php

namespace App\Filament\Resources\AppointmentSlots\Pages;

use App\Filament\Resources\AppointmentSlots\AppointmentSlotResource;
use Filament\Resources\Pages\ListRecords;

class ListAppointmentSlots extends ListRecords
{
    protected static string $resource = AppointmentSlotResource::class;
}
