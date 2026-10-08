<?php

namespace App\Filament\Resources\GasOrders\Pages;

use App\Filament\Resources\GasOrders\GasOrderResource;
use Filament\Resources\Pages\ListRecords;

class ListGasOrders extends ListRecords
{
    protected static string $resource = GasOrderResource::class;
}
