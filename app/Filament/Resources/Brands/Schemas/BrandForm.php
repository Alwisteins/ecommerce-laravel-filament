<?php

namespace App\Filament\Resources\Brands\Schemas;

use App\Models\Brand;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class BrandForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                ->required()
                ->maxLength(255)
                ->live(onBlur:true)
                ->afterStateUpdated(fn (string $operation, $state, Set $set) => 
                    $operation === 'create' ? $set('slug', Str::slug($state)) : null),
                TextInput::make('slug')
                    ->required()
                    ->maxLength(255)
                    ->disabled()
                    ->dehydrated()
                    ->unique(Brand::class, 'slug',  ignoreRecord: true),
                FileUpload::make('image')
                    ->image()
                    ->directory('brands'),
                Toggle::make('is_active')
                    ->required(),
            ]);
    }
}
