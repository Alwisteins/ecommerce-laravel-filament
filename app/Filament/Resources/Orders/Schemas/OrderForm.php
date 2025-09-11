<?php

namespace App\Filament\Resources\Orders\Schemas;

use App\Models\Product;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;

use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Number;

class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Group::make()->schema([
                    Section::make('Order Information')->schema([
                        Select::make('user_id')
                        ->label('Customer')
                        ->relationship('user', 'name')
                        ->searchable()
                        ->preload()
                        ->required(),

                        Select::make('payment_method')
                        ->options([
                            'bca' => "BCA",
                            'cod' => 'Cash On Delivery',
                            'dana' => 'Dana'
                        ]),

                        Select::make('payment_status')
                        ->options([
                            'pending' => 'Pending',
                            'paid' => 'Paid',
                            'failed' => 'Failed'
                        ]),

                        ToggleButtons::make('status')
                        ->inline()
                        ->default('new')
                        ->required()
                        ->options([
                            'new' => 'New',
                            'processing' => 'Processing',
                            'shipped' => 'Shipped',
                            'delivered' => 'Delivered',
                            'failed' => 'Failed'
                        ])
                        ->colors([
                            'new' => 'info',
                            'processing' => 'warning',
                            'shipped' => 'success',
                            'delivered' => 'success',
                            'failed' => 'danger'
                        ])
                        ->icons([
                            'new' =>  Heroicon::Sparkles,
                            'processing' => Heroicon::ArrowPath,
                            'shipped' => Heroicon::Truck,
                            'delivered' => Heroicon::CheckBadge,
                            'failed' => Heroicon::XCircle
                        ]),

                        Select::make('currency')
                        ->options([
                            'idr' => 'IDR',
                            'usd' => 'USD',
                            'eur' => 'EUR',
                        ]),

                        Select::make('shipping_method')
                        ->options([
                            'jnt' => 'JNT',
                            'jne' => 'JNE',
                            'sicepat' => 'Sicepat'
                        ]),

                        Textarea::make('notes')
                        ->columnSpanFull()
                    ])->columns(2),

                    Section::make('Order Items')->schema([
                        Repeater::make('items')
                        ->relationship()
                        ->schema([
                            Select::make('product_id')
                            ->relationship('product', 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->distinct()
                            ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                            ->columnSpan(4)
                            ->reactive()
                            ->afterStateUpdated(fn ($state, Set $set) => $set('unit_amount', Product::find($state)
                            -> price ?? 0))
                            ->afterStateUpdated(fn ($state, Set $set) => $set('total_amount', Product::find($state)
                            -> price ?? 0)),

                            TextInput::make('quantity')
                            ->numeric()
                            ->required()
                            ->default(1)
                            ->minValue(1)
                            ->columnSpan(2)
                            ->reactive()
                            ->afterStateUpdated(fn ($state, Set $set, Get $get) => $set('total_amount', $state * $get('unit_amount'))),
                            
                            TextInput::make('unit_amount')
                            ->numeric()
                            ->required()
                            ->disabled()
                            ->dehydrated()
                            ->columnSpan(3),

                            TextInput::make('total_amount')
                            ->numeric()
                            ->required()
                            ->dehydrated()
                            ->columnSpan(3),
                        ])->columns(12),

                        Placeholder::make('grand_total')
                        ->label('Grand Total')
                        ->content(function (Get $get) {
                            $total = 0;

                            if ($repeaters = $get('items')) {
                                foreach ($repeaters as $key => $repeater) {
                                    $total += $get("items.{$key}.total_amount") ?? 0;
                                }
                            }

                            return Number::currency($total, 'IDR');
                        }),
                        
                        Hidden::make('grand_total')
                        ->default(0),
                    ])
                ])->columnSpanFull()
            ]);
    }
}
