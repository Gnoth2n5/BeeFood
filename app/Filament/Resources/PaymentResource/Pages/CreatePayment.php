<?php

namespace App\Filament\Resources\PaymentResource\Pages;

use App\Filament\Resources\PaymentResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePayment extends CreateRecord
{
    protected static string $resource = PaymentResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Set default values if not provided
        if (!isset($data['transaction_date'])) {
            $data['transaction_date'] = now();
        }
        
        if (!isset($data['status'])) {
            $data['status'] = 'pending';
        }

        return $data;
    }
}
