<?php


namespace App\Http\Controllers\Store;


use App\Http\Controllers\Controller;
use App\Models\Store\AdditionalProduct;
use App\Models\Store\Cart;
use App\Models\Store\Product;
use App\Models\Store\Promo;
use App\Models\Store\PromoUser;
use App\Models\Store\Purchase;
use App\Services\Store\CheckCountTransaction;
use App\Services\Store\Purchasing\Payers\Payer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;

class CartController extends Controller
{
    public function index()
    {
        return $this->view('store.cart', [
            'items' => Cart::with(['product', 'product.discount', 'product.category'])
                ->whereHas('product', function ($query) {
                    return $query->isEnabled();
                })
                ->whereHas('product.category', function ($query) {
                    return $query->isEnabled();
                })
                ->where('user_id', $this->user()->id)
                ->whereNull('purchase_id')
                ->onlyPurchased(false)
                ->take(100)
                ->get(),
            'recommended' => Product::with('category')
				->whereNotNull('price_coins')
                ->whereHas('category', function ($query) {return $query->isEnabled();})
                ->isEnabled()
                ->inRandomOrder()
                ->take(4)
                ->get(),
            'settings' => array_merge([
                'count_transactions' => config('site.store.count_transactions'),
                'store_url' => route('store.home'),
                'coins_url' => route('store.home', ['cat' => 4])
            ], config('site.store', [])),
            'userData' => [
                'name' => $this->user()->playername,
                'balance' => $this->user()->coins->coins
            ],
            'lang' => [
                'store' => Lang::get('store'),
                'cart' => Lang::get('cart'),
                'words' => Lang::get('words')
            ]
        ]);
    }

    public function put(Request $request, Product $product)
    {
        $request->validate([
            'amount' => 'required|integer|min:1'
        ]);

        if (!$product->enabled || !$product->category->enabled || !is_null($product->price_rub)) {
            abort(403, __('cart.responses.product_unavailable'));
        }

        $cart = new Cart([
            'user_id' => $this->user()->id,
            'product_id' => $product->id,
            'amount' => (int) $request->post('amount')
        ]);
        $cart->save();
        $cart->product = $product;

        return new JsonResponse(['message' => __('cart.responses.product_added_to_cart'), 'cart' => $cart->toArray()]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|integer',
            'amount' => 'required|integer|min:1'
        ]);

        $cart = Cart::with('product')->onlyPurchased(false)->findOrFail((int) $request->post('id'));
        if ($cart->user->id !== $this->user()->id) {
            abort(403, __('cart.responses.product_unavailable'));
        }


        $cart->amount = (int) $request->post('amount');
        $cart->save();

        return new JsonResponse([
            'message' => __('cart.responses.cart_item_updated'),
            'cart' => $cart->toArray()
        ]);
    }

    public function delete(Request $request, Cart $cart)
    {
        if ($cart->user->id !== $this->user()->id || !is_null($cart->purchase_id)) {
            abort(403, __('cart.responses.product_unavailable'));
        }

        $cart->delete();

        return new JsonResponse(['message' => __('cart.responses.product_deleted_from_cart')]);
    }

    public function checkPromo(Request $request)
    {
        $request->validate([
            'code' => 'required|string'
        ]);

        /* @var Promo $promo */
        $promo = Promo::findByCode($request->post('code'))->first();
        if (is_null($promo)) {
            return new JsonResponse(['error' => __('cart.responses.promo_not_found')]);
        }

        if (!is_null(PromoUser::findByUserAndPromo($this->user(), $promo)->first())) {
            return new JsonResponse(['error' => __('cart.responses.promo_already_activated')]);
        }

        return new JsonResponse([
            'message' => __('cart.responses.promo_activated'),
            'promo' => $promo->id,
            'discount' => $promo->discount
        ]);
    }

    public function pay(Request $request, CheckCountTransaction $checker)
    {
        $user = $this->user();

        if (!$checker->check($user)) {
            $error = __('cart.responses.transactions_limit');
            $error['text'] = str_replace('%count%', config('site.store.count_transactions'), $error['text']);
            return new JsonResponse(['error' => $error]);
        }

        $promoCode = $request->post('promo');
        if (!empty(trim($promoCode))) {
            $promo = Promo::find($promoCode);
            if (is_null($promo)) {
                abort('404', __('cart.responses.promo_expired'));
            }

            if (!is_null(PromoUser::findByUserAndPromo($user, $promo)->first())) {
                abort('403', __('cart.responses.promo_already_activated'));
            }
        }

        /**
         * Получаем товары в корзине
         * @var \Illuminate\Support\Collection $cart
         */
        $cart = Cart::with(['product', 'product.discount', 'product.category'])
            ->whereHas('product', function ($query) {
                return $query->isEnabled();
            })
            ->whereHas('product.category', function ($query) {
                return $query->isEnabled();
            })
            ->byUser($user)
            ->onlyPurchased(false)
            ->get();

        if ($cart->isEmpty()) {
            abort('404', __('cart.responses.cart_empty'));
        }

        /*
         * Подсчитываем общую сумму с учетом скидок
         */
        $cost = 0;
        /* @var Cart $item */
        foreach ($cart as $item)
        {
            if(is_null($item->product->price_coins)) {
                $item->delete();
                continue;
            }
            $cost += $item->getPrice();
        }

        /*
         * Применяем скидку из промо-кода (если есть)
         */
        $discount = isset($promo) ? $promo->discount : 0;
        if ($discount > 0 && $discount < 100) {
            $cost -= floor($cost * ($discount / 100));
        }

        if ($cost < 1) {
            abort(500, 'Invalid cost!');
        }

        /*
         * Проверяем баланс игрока
         * и снимаем монеты
         */
        if ($user->getCoins() < $cost) {
            $error = __('cart.responses.not_enough_coins');
            $error['link_url'] = route('store.index');
            return new JsonResponse(['error' => $error, 'need' => $cost]);
        }

        $user->withdrawCoins($cost);
        $user->save();

        /*
         * Создаем покупку
         */
        $purchase = new Purchase([
            'user_id' => $user->id,
            'cost' => $cost,
            'currency' => 'coins',
            'ip' => $request->ip(),
            'promo' => isset($promo) ? $promo->code : null
        ]);
        $purchase->completed_at = $purchase->freshTimestamp();
        $purchase->save();

        if (isset($promo)) {
            $promoUser = new PromoUser([
                'user_id' => $user->id,
                'promo_id' => $promo->id
            ]);
            $promoUser->save();
        }

        /*
         * Выдаем товары на склад
         * и обновляем количество покупок товара
         * и записываем id покупки в корзину
         */
        /* @var Cart $item */
        foreach ($cart as $item)
        {
            $item->product->count_buys++;
            $item->product->save();

            $item->purchase_id = $purchase->id;
            $item->save();
            $item->purchase = $purchase;
            $item->user = $user;

            $item->distribute();

            /* @var AdditionalProduct $additional */
            foreach ($item->product->additionals as $additional)
            {
                app($additional->additional->category->distributor)
                    ->distributeProduct($user, $additional->additional, $item->amount * $additional->amount);
            }
        }

        return new JsonResponse([
            'message' => __('cart.responses.buy_success'),
            'balance' => $user->coins->coins
        ]);
    }

    public function buy(Request $request, Product $product)
    {
        $request->validate([
            'amount' => 'required|integer|min:1'
        ]);

        if (!$product->enabled || !$product->category->enabled || is_null($product->price_rub)) {
            abort(403, __('cart.responses.product_unavailable'));
        }

        $purchase = new Purchase([
            'user_id' => $this->user()->id,
            'cost' => $product->getPrice(),
            'currency' => 'coins',
            'ip' => $request->ip()
        ]);
        $purchase->save();

        $cart = new Cart([
            'user_id' => $this->user()->id,
            'product_id' => $product->id,
            'amount' => (int) $request->post('amount')
        ]);
        $cart->purchase_id = $purchase->id;
        $cart->save();

        /* @var Payer $payer */
        $payer = app(config('site.store.payer'));
        $url = $payer->paymentUrl($purchase);

        return new JsonResponse(['url' => $url]);
    }
}
