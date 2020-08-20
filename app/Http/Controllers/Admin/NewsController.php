<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Str;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\News\AddRequest;
use App\Http\Requests\Admin\News\EditRequest;
use App\Models\News;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return $this->view('admin.news.index', [
            'news' => News::latest('id')->paginate(30)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return $this->view('admin.news.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  AddRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(AddRequest $request)
    {
        $image = $request->file('image');

        $news = new News($request->validated());
        $imageName = Str::random(4) . '_' . time() . '.' . $image->getClientOriginalExtension();
        $news->image = $imageName;
        $image->move(public_path('uploads/news'), $imageName);
        $news->save();

        return $this->redirectSuccess('admin.news.index', 'Новость добавлена.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\News  $news
     * @return \Illuminate\Http\Response
     */
    public function show(News $news)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\News  $news
     * @return \Illuminate\View\View
     */
    public function edit(News $news)
    {
        return $this->view('admin.news.edit', [
            'article' => $news
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  EditRequest  $request
     * @param  \App\Models\News  $news
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(EditRequest $request, News $news)
    {
        $news->update($request->validated());

        $oldImage = null;
        if (!is_null($request->file('image'))) {
            $oldImage = $news->image;
            $image = $request->file('image');
            $imageName = Str::random(4) . '_' . time() . '.' . $image->getClientOriginalExtension();
            $news->image = $imageName;
            $image->move(public_path('uploads/news'), $imageName);
        }
        $news->save();

        if (!is_null($oldImage)) {
            $file = public_path('uploads/news/' . $oldImage);
            unlink($file);
        }

        return $this->backSuccess('Новость обновлена.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\News  $news
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy(News $news)
    {
        $news->delete();
        $file = public_path('uploads/news/' . $news->image);
        @unlink($file);

        return $this->backSuccess('Новость удалена.');
    }
}
