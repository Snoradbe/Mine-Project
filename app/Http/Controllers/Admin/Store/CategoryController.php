<?php

namespace App\Http\Controllers\Admin\Store;

use App\Http\Controllers\Controller;
use App\Lang;
use App\Models\Store\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return $this->view('admin.store.categories.index', [
            'categories' => Category::all()
        ]);
    }

    private function getCategoryData(Request $request): array
    {
        $nameLangs = ['name' => 'required|string|max:255'];
        foreach (Lang::ALLOWED_LANGS as $lang)
        {
            if ($lang != 'ru') {
                $nameLangs['name_' . $lang] = 'required|string|max:255';
            }
        }

        $request->validate(array_merge([
            'need_auth' => 'nullable',
            'enabled' => 'nullable',
            'distributor' => 'required|string|in:' . implode(',', config('site.store.distributors', []))
        ], $nameLangs));

        $nameLangs = ['name' => $request->post('name')];
        foreach (Lang::ALLOWED_LANGS as $lang)
        {
            if ($lang != 'ru') {
                $nameLangs['name_' . $lang] = $request->post('name_' . $lang, '');
            }
        }

        return array_merge([
            'need_auth' => (bool) $request->post('need_auth', false),
            'enabled' => (bool) $request->post('enabled', false),
            'distributor' => $request->post('distributor')
        ], $nameLangs);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return $this->view('admin.store.categories.create', [
            'distributors' => config('site.store.distributors', [])
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
            'image' => 'required|file|image|mimes:svg'
        ]);

        $category = new Category($this->getCategoryData($request));
        $category->save();

        $image = $request->file('image');
        $image->move(public_path('img/store'), $category->id . '.svg');

        return $this->redirectSuccess('admin.store.categories.index', 'Категория добавлена');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Store\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Store\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        return $this->view('admin.store.categories.edit', [
            'category' => $category,
            'distributors' => config('site.store.distributors', [])
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Store\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'image' => 'nullable|file|image|mimes:svg'
        ]);

        $category->update($this->getCategoryData($request));

        if (!is_null($request->file('image'))) {
            $image = $request->file('image');
            $image->move(public_path('img/store'), $category->id . '.svg');
        }

        return $this->backSuccess('Категория изменена');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Store\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        $category->delete();

        return $this->redirectSuccess('admin.store.categories.index', 'Категория удалена');
    }
}
