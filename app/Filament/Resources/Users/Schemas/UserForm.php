<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\CreateRecord;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')->required(),
                TextInput::make('email')->maxLength(255)->unique(ignoreRecord:true)->required(),
                DateTimePicker::make('email_verified_at')->label('Email Verified At')->required(),
                TextInput::make('password')->dehydrated(fn ($state) => filled($state))->required(fn ($liveware): bool => $liveware instanceof CreateRecord),
            ]);
    }
}
