<?php


namespace App\Services\Store\Purchasing\Payers;


use App\Exceptions\BaseException;
use App\Helpers\StoreHelper;
use App\Models\Store\Purchase;
use App\Services\Store\Purchasing\Payments\Unitpay\Checkout;
use App\Services\Store\Purchasing\Payments\Unitpay\Payment;

class UnitpayPayer implements Payer
{
    private const NAME = 'unitpay';

    private $checkout;

    public function __construct(Checkout $checkout)
    {
        $this->checkout = $checkout;
    }

    public function getName(): string
    {
        return self::NAME;
    }

    public function paymentUrl(Purchase $purchase): string
    {
        $desc = config('app.name') . ': '
            . $purchase->user->playername . ', '
            . StoreHelper::getColumnValue('name', $purchase->cart->first()->product);

        $payment = new Payment($purchase->id, $purchase->cost, $desc);

        return $this->checkout->paymentUrl($payment);
    }

    public function validate(Purchase $purchase, array $data): bool
    {
        if((int)$data['params']['sum'] < $purchase->cost) {
            throw new BaseException("Invalid sum (need: '{$purchase->cost}')");
        }

        if($this->checkout->validate($data)) {
            //if($data['method'] !== 'pay') {
            //    throw new BaseException($this->successResponse('OK'));
            //}
            return true;
        }

        return false;
    }

    public function purchaseId(array $data): int
    {
        return (int) $data['params']['account'];
    }

    public function successResponse(string $message)
    {
		header('Content-Type: application/json');
        return json_encode(['response' => ['message' => $message]]);
    }

    public function errorResponse(string $message)
    {
		header('Content-Type: application/json');
        return json_encode(['error' => ['message' => $message]]);
    }
}
