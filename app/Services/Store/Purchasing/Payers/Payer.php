<?php


namespace App\Services\Store\Purchasing\Payers;


use App\Models\Store\Purchase;

interface Payer
{
    public function paymentUrl(Purchase $purchase): string;

    public function getName(): string;

    public function validate(Purchase $purchase, array $data): bool;

    public function purchaseId(array $data): int;

    public function successResponse(string $message);

    public function errorResponse(string $message);
}
