<?php

namespace App\Filament\Resources\UserShopResource\Pages;

use App\Filament\Resources\UserShopResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUserShop extends EditRecord
{
    protected static string $resource = UserShopResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

