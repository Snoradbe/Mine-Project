<?php


namespace App\Http\Controllers\Store;


use App\Http\Controllers\Controller;
use App\Models\Store\AdditionalProduct;
use App\Models\Store\Cart;
use App\Models\Store\Purchase;
use App\Services\Store\Purchasing\Payers\Pool;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    private $pool;

    public function __construct(Pool $pool)
    {
        $this->pool = $pool;
    }

    public function pay(Request $request, string $payerName)
    {
        $payer = $this->pool->find($payerName);
        if (is_null($payer)) {
            abort(404, "Payer '$payerName' not found");
        }

        $data = $request->all();

        try {
            /* @var Purchase $purchase */
            $purchase = Purchase::find($payer->purchaseId($data));
			if (is_null($purchase) || !is_null($purchase->completed_at)) {
				return $payer->errorResponse('Purchase not found or inactive');
			}

            if (!$payer->validate($purchase, $data)) {
                return $payer->errorResponse('Invalid payment data');
            }
        } catch (\Exception $exception) {
            return $payer->errorResponse($exception->getMessage());
        }
		
		if ($data['method'] != 'pay') {
			return $payer->successResponse('OK');
		}

        $purchase->completed_at = $purchase->freshTimestamp();
        $purchase->save();

        /* @var Cart $cart */
        $cart = $purchase->cart->first();
        $cart->product->count_buys++;
        $cart->product->save();

        $cart->distribute();

        /* @var AdditionalProduct $additional */
        foreach ($cart->product->additionals as $additional)
        {
            app($additional->additional->category->distributor)
                ->distributeProduct($cart->user, $additional->additional, $additional->amount);
        }
		
		return $payer->successResponse('OK');
    }
}
