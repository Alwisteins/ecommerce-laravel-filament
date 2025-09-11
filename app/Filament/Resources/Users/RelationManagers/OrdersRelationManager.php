<?php

namespace App\Filament\Resources\Users\RelationManagers;

use App\Filament\Resources\Orders\OrderResource;
use App\Models\Order;
use Filament\Actions\Action;
use Filament\Actions\AssociateAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DissociateAction;
use Filament\Actions\DissociateBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class OrdersRelationManager extends RelationManager
{
    protected static string $relationship = 'orders';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
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
                
            ])
            ->recordActions([
                Action::make('View Order')
                ->url(fn (Order $record):string => OrderResource::getUrl('view', ['record' => $record]))
                ->color('info')
                ->icon(Heroicon::Eye),
                DeleteAction::make()
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
