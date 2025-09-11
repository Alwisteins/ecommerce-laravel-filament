<?php

namespace App\Filament\Resources\Orders\Widgets;

use App\Models\Order;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Number;

class OrderStats extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('New Orders', Order::query()->where('status', 'new')->count())
                ->icon('heroicon-o-shopping-cart'),

                Stat::make('New Orders', Order::query()->where('status', 'processing')->count())
                ->icon('heroicon-o-arrow-path'),

                Stat::make('New Orders', Order::query()->where('status', 'shipped')->count())
                ->icon('heroicon-o-truck'),

                Stat::make('Average Price', Number::currency(Order::query()->avg('grand_total'), 'IDR'))
        ];
    }
}
