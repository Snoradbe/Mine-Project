<?php

namespace App\Http\Controllers\Admin\Store;

use App\Helpers\Str;
use App\Http\Controllers\Controller;
use App\Lang;
use App\Models\Server;
use App\Models\Store\AdditionalProduct;
use App\Models\Store\Category;
use App\Models\Store\Discount;
use App\Models\Store\Product;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->view('admin.store.products.index', [
            'products' => Product::paginate(100),
        ]);
    }

    private function getProductData(Request $request): array
    {
        $nameLangs = ['name' => 'required|string|max:255'];
        $descLangs = ['desc' => 'nullable|string'];
        foreach (Lang::ALLOWED_LANGS as $lang)
        {
            if ($lang != 'ru') {
                $nameLangs['name_' . $lang] = 'required|string|max:255';
                $descLangs['desc_' . $lang] = 'nullable|string';
            }
        }

        $request->validate(array_merge([
            'category' => 'required|exists:store_categories,id',
            'server' => 'nullable|exists:servers,name',
            'discount' => 'nullable|exists:store_discounts,id',
            'price_rub' => 'nullable|integer|min:0',
            'price_coins' => 'nullable|integer|min:0',
            'amount' => 'required|integer|min:1',
            'enabled' => 'nullable',
            'data' => 'nullable|string',
            'additionals' => 'nullable|string'
        ], $nameLangs, $descLangs));

        $server = !empty(trim($request->post('server', ''))) ? $request->post('server') : null;
        $discount = !empty(trim($request->post('discount'))) ? (int) $request->post('discount') : null;

        $price_rub = (int) $request->post('price_rub', 0);
        $price_coins = (int) $request->post('price_coins', 0);

        if ($price_rub < 1 && $price_coins < 1) {
            return $this->backError('Выберите хотябы 1 цену!')->withInput($request->post());
        }

        if ($price_rub > 0 && $price_coins > 0) {
            return $this->backError('Выберите только 1 цену!')->withInput($request->post());
        }

        $nameLangs = ['name' => $request->post('name')];
        $descLangs = ['descr' => nl2br((string) $request->post('desc', ''))];
        foreach (Lang::ALLOWED_LANGS as $lang)
        {
            if ($lang != 'ru') {
                $nameLangs['name_' . $lang] = $request->post('name_' . $lang);
                $descLangs['descr_' . $lang] = nl2br((string) $request->post('desc_' . $lang, ''));
            }
        }

        $category = (int) $request->post('category');
        $amount = (int) $request->post('amount');
        $enabled = (bool) $request->post('enabled');
        $data = $request->post('data', '') ?: '';

        return array_merge([
            'category_id' => $category,
            'servername' => $server,
            'price_rub' => $price_rub < 1 ? null : $price_rub,
            'price_coins' => $price_coins < 1 ? null : $price_coins,
            'amount' => $amount,
            'enabled' => $enabled,
            'data' => $data,
            'discount_id' => $discount
        ], $nameLangs, $descLangs);
    }

    private function addAdditionalProducts(Product $product, ?string $additionals): void
    {
        AdditionalProduct::where('product_id', $product->id)->delete();

        if (empty(trim($additionals))) {
            return;
        }

        $additionals = explode(',', trim($additionals));
        if (empty($additionals)) {
            return;
        }

        $additionalsData = [];
        foreach ($additionals as $additional)
        {
            $additionalExpl = explode(':', $additional);
            if (
                count($additionalExpl) == 2
                && is_numeric($additionalExpl[0])
                && is_numeric($additionalExpl[1])
                && $additionalExpl[1] > 0
            ) {
                $additionalsData[(int) $additionalExpl[0]] = (int) $additionalExpl[1];
            }
        }

        if (!empty($additionalsData)) {
            $additionalsProducts = Product::whereIn('id', array_keys($additionalsData))->get();
            foreach ($additionalsProducts as $additionalProduct)
            {
                if ($product->id != $additionalProduct->id) {
                    $additional = new AdditionalProduct([
                        'product_id' => $product->id,
                        'additional_id' => $additionalProduct->id,
                        'amount' => $additionalsData[(int) $additionalProduct->id]
                    ]);
                    $additional->save();
                }
            }
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return $this->view('admin.store.products.create', [
            'categories' => Category::all(),
            'servers' => Server::all(),
            'discounts' => Discount::isActive()->get()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|file|image'
        ]);

        $product = new Product($this->getProductData($request));
        $product->save();

        $image = $request->file('image');
        $imageName = Str::random('3') . '-' . $product->id . '.' . $image->getClientOriginalExtension();
        $image->move(public_path('img/store/products'), $imageName);

        $product->img = $imageName;
        $product->save();

        $this->addAdditionalProducts($product, $request->post('additionals', ''));

        return $this->redirectSuccess('admin.store.products.index', 'Товар добавлен');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Store\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Store\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        return $this->view('admin.store.products.edit', [
            'product' => $product,
            'categories' => Category::all(),
            'servers' => Server::all(),
            'discounts' => Discount::isActive()->get()
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Store\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'image' => 'nullable|file|image'
        ]);

        $product->update($this->getProductData($request));

        $image = $request->file('image');
        if (!is_null($image)) {
            $imageName = Str::random('3') . '-' . $product->id . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('img/store/products'), $imageName);

            if ($product->img != $imageName) {

                $oldImage = public_path('img/store/products/' . $product->img);
                if (is_file($oldImage)) {
                    @unlink($oldImage);
                }

                $product->img = $imageName;
                $product->save();
            }
        }

        $this->addAdditionalProducts($product, $request->post('additionals', ''));

        return $this->backSuccess('Товар изменен');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Store\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        $product->delete();

        return $this->backSuccess('Товар удален');
    }
}
