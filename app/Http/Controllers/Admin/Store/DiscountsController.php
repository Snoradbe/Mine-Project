<?php

namespace App\Http\Controllers\Admin\Store;

use App\Http\Controllers\Controller;
use App\Lang;
use App\Models\Server;
use App\Models\Store\Category;
use App\Models\Store\Discount;
use App\Models\Store\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class DiscountsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->view('admin.store.discounts.index', [
            'discounts' => Discount::all()
        ]);
    }

    private function getDiscountData(Request $request): array
    {
        $nameLangs = ['name' => 'required|string|max:255'];
        foreach (Lang::ALLOWED_LANGS as $lang)
        {
            if ($lang != 'ru') {
                $nameLangs['name_' . $lang] = 'required|string|max:255';
            }
        }

        $request->validate(array_merge([
            'discount' => 'required|integer|min:1|max:99',
            'date_start' => 'required|date',
            'date_end' => 'required|date',
        ], $nameLangs));

        $nameLangs = ['name' => $request->post('name')];
        foreach (Lang::ALLOWED_LANGS as $lang)
        {
            if ($lang != 'ru') {
                $nameLangs['name_' . $lang] = $request->post('name_' . $lang, '');
            }
        }

        return array_merge([
            'discount' => (int) $request->post('discount'),
            'date_start' => new Carbon($request->post('date_start')),
            'date_end' => new Carbon($request->post('date_end')),
        ], $nameLangs);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return $this->view('admin.store.discounts.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $discount = new Discount($this->getDiscountData($request));
        $discount->save();

        return $this->redirectSuccess('admin.store.discounts.index', 'Скидка добавлена');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Store\Discount  $discount
     * @return \Illuminate\Http\Response
     */
    public function show(Discount $discount)
    {
        return $this->view('admin.store.discounts.show', [
            'discount' => $discount,
            'categories' => Category::all(),
            'servers' => Server::onlyGame()->get(),
            'products' => Product::all()
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Store\Discount  $discount
     * @return \Illuminate\Http\Response
     */
    public function edit(Discount $discount)
    {
        return $this->view('admin.store.discounts.edit', [
            'discount' => $discount
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Store\Discount  $discount
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Discount $discount)
    {
        $discount->update($this->getDiscountData($request));

        return $this->backSuccess('Скидка изменена');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Store\Discount  $discount
     * @return \Illuminate\Http\Response
     */
    public function destroy(Discount $discount)
    {
        $discount->delete();

        return $this->backSuccess('admin.store.discounts.index', 'Скидка удалена');
    }

    public function setDiscounts(Request $request, Discount $discount)
    {
        $request->validate([
            'type' => 'required|integer'
        ]);

        switch ((int) $request->post('type'))
        {
            //выдача на все | категорию | сервер
            case 1:
                $request->validate([
                    'category' => 'nullable|integer|exists:store_categories,id',
                    'server' => 'nullable|string|exists:servers,name',
                ]);

                /**
                 * @var Category $category
                 * @var Server $server
                 */
                $category = $server = null;
                if (!is_null($request->post('category'))) {
                    $category = Category::findOrFail((int) $request->post('category'));
                }

                if (!is_null($request->post('server'))) {
                    $server = Server::findOrFail($request->post('server'));
                }

                Product::setDiscountFor($discount, $category, $server);
                break;

            //выдача на выбранные товары
            case 2:
                $request->validate([
                    'products' => 'required|array',
                    'products.*' => 'required|integer'
                ]);

                Product::setDiscountForProducts($discount, $request->post('products'));
                break;
        }

        return $this->backSuccess('Скидка установлена на товары');
    }

    public function removeDiscounts()
    {
        Product::setDiscountForAll(null);

        return $this->backSuccess('Скидка удалена со всех товаров');
    }
}
