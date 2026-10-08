<?php

namespace App\Filament\Resources\GasOrders;

use App\Filament\Resources\GasOrders\Pages\ListGasOrders;
use App\Filament\Resources\GasOrders\Pages\ViewGasOrder;
use App\Filament\Resources\GasOrders\Schemas\GasOrderInfolist;
use App\Filament\Resources\GasOrders\Tables\GasOrdersTable;
use App\Models\GasOrder;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class GasOrderResource extends Resource
{
    protected static ?string $model = GasOrder::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedFire;

    protected static ?string $modelLabel = 'pedido de gás';

    protected static ?string $pluralModelLabel = 'Pedidos de Gás';

    protected static ?string $navigationLabel = 'Pedidos de Gás';

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit(mixed $record): bool
    {
        return false;
    }

    public static function getNavigationBadge(): ?string
    {
        $unread = GasOrder::whereNull('read_at')->count();

        return $unread > 0 ? (string) $unread : null;
    }

    public static function infolist(Schema $schema): Schema
    {
        return GasOrderInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return GasOrdersTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListGasOrders::route('/'),
            'view' => ViewGasOrder::route('/{record}'),
        ];
    }
}
