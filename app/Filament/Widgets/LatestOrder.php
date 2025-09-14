<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\Orders\OrderResource;
use App\Models\Order;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;

class LatestOrder extends TableWidget
{
    protected int | string | array $columnSpan = 'full';

    protected static ?int $sort = 2;

    public function table(Table $table): Table
    {
        return $table
            ->query(OrderResource::getEloquentQuery())
            ->defaultPaginationPageOption(5)
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('id')
                    ->label('Order ID')
                    ->searchable(),

                TextColumn::make('grand_total')
                    ->money('IDR'),

                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state):string => match($state) {
                        'new' => 'info',
                        'processing' => 'warning',
                        'shipped' => 'success',
                        'delivered' => 'success',
                        'failed' => 'danger'
                    })
                    ->icon(fn (string $state) => match ($state) {
                        'new' => Heroicon::Sparkles,
                        'processing' => Heroicon::ArrowPath,
                        'shipped' => Heroicon::Truck,
                        'delivered' => Heroicon::CheckBadge,
                        'failed' => Heroicon::XCircle,
                    })
                    ->sortable(),

                    TextColumn::make('payment_method')
                    ->sortable()
                    ->searchable(),

                    TextColumn::make('payment_status')
                    ->badge()
                    ->sortable()
                    ->searchable(),

                    TextColumn::make('created_at')
                    ->label('Order Date')
                    ->datetime()
            ])
            ->filters([
                //
            ])
            ->headerActions([
                //
            ])
            ->recordActions([
                Action::make('View Order')
                ->url(fn (Order $record): string => OrderResource::getUrl('view', ['record' => $record]))
                ->icon(HeroIcon::Eye)
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    //
                ]),
            ]);
    }
}
