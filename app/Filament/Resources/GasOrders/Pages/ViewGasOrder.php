<?php

namespace App\Filament\Resources\GasOrders\Pages;

use App\Filament\Resources\GasOrders\GasOrderResource;
use Filament\Resources\Pages\ViewRecord;

class ViewGasOrder extends ViewRecord
{
    protected static string $resource = GasOrderResource::class;

    public function mount(int|string $record): void
    {
        parent::mount($record);

        if ($this->record->read_at === null) {
            $this->record->update(['read_at' => now()]);
        }
    }
}
