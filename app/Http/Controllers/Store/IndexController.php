<?php


namespace App\Http\Controllers\Store;


use App\Http\Controllers\Controller;
use App\Models\Server;
use App\Models\Store\Category;
use App\Models\Store\Discount;
use App\Models\Store\Product;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;

class IndexController extends Controller
{
    public function index(Request $request)
    {
        $user = $this->user();
        $userData = [];
        if (!is_null($user)) {
            $userData = [
                'name' => $user->playername,
                'balance' => $user->coins->coins,
                'logged' => true
            ];
        }

        return $this->view('store.index', $this->viewData($request, $userData));
    }

    public function login(Request $request)
    {
        $request->validate([
            'nickname' => 'required|string'
        ]);

        /* @var User $user */
        $user = User::findByName($request->post('nickname'))->take(1)->first();
        if (is_null($user)) {
            return back()->withErrors(__('store.responses.nickname_not_found'));
        }

        return $this->view('store.index', $this->viewData($request, [
            'name' => $user->playername,
            'balance' => 0,
            'logged' => false
        ]));
    }

    private function viewData(Request $request, array $userData): array
    {
        return [
            'categories' => Category::isEnabled()
                ->with('products')
                ->withCount(['products' => function ($query) {
                    return $query->isEnabled();
                }])->get(),
            'servers' => Server::getAllFromCache(true),
            'discounts' => Discount::isActive()->get(),
            'popular' => Product::popular()
                ->whereHas('category', function ($query) {return $query->where('id', '!=', 2)->isEnabled();})
                ->isEnabled()->take(config('site.store.limit', 12))->get(),
            'settings' => array_merge([
				'disabled_servers_perks' => config('site.store.disabled_servers_perks', []),
                'count_transactions' => config('site.store.count_transactions'),
				'login_url' => route('login'),
                'cart_url' => route('store.cart')
			], config('site.store', [])),
            'userData' => $userData,
            'lang' => [
                'store' => Lang::get('store'),
                'cart' => Lang::get('cart'),
                'words' => Lang::get('words')
            ],
            'selectedCategory' => $request->get('cat', 0)
        ];
    }

    public function loadProducts(Request $request)
    {
        $request->validate([
            'category' => 'nullable|integer',
            'server' => 'nullable|string',
            'discount' => 'nullable|integer',
            'name' => 'nullable|string',
        ]);

        $category = $request->post('category');
        if (!is_null($category)) {
            $category = Category::isEnabled()->findOrFail((int) $category);
        }

        $server = $request->post('server');
        if (!is_null($server)) {
            $server = Server::findOrFail($server);
        }

        $discount = $request->post('discount');
        if (!is_null($discount)) {
            $discount = Discount::isActive()->findOrFail((int) $discount);
        }

        $name = trim($request->post('name', ''));

		$query = Product::hasCategory($category)
            ->whereHas('category', function ($query) {
                return $query->isEnabled();
            })
            ->hasServer($server)
            ->hasDiscount($discount)
            ->byName($name)
            ->isEnabled();

		if (is_null($category) || $category->id != 2) {
			$query = $query->whereHas('category', function ($query) {
                return $query->where('id', '!=', 2);
            });
		}

        $search = $query->paginate(12);

        return new JsonResponse($search);
    }
}
