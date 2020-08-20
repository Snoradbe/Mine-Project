<?php

namespace App\Http\Controllers\Admin\Store;

use App\Helpers\Str;
use App\Http\Controllers\Controller;
use App\Models\Store\Promo;
use App\Models\Store\PromoUser;
use Illuminate\Http\Request;

class PromoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->view('admin.store.promo.index', [
            'promos' => Promo::all()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return $this->view('admin.store.promo.create');
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
            'code' => 'nullable|string|max:16',
            'discount' => 'required|integer|min:1|max:99'
        ]);

        $code = $request->post('code');
        if (empty($code)) {
            do {
                $code = Str::random(16);
            } while(is_null(Promo::findByCode($code)->first()));
        } else {
            if (!is_null(Promo::findByCode($code)->first())) {
                return $this->backError('Промо-код с таким кодом уже есть')->withInput($request->post());
            }
        }

        $promo = new Promo([
            'code' => $code,
            'discount' => (int) $request->post('discount')
        ]);
        $promo->save();

        return $this->redirectSuccess('admin.store.promo.index', 'Промо-код добавлен');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Store\Promo  $promo
     * @return \Illuminate\Http\Response
     */
    public function show(Promo $promo)
    {
        return $this->view('admin.store.promo.show', [
            'promo' => $promo,
            'activations' => PromoUser::with('user')
                ->where('promo_id', $promo->id)
                ->latest('id')
                ->paginate(50)
        ]);
    }

    public function deleteActivation(PromoUser $promoUser)
    {
        $promoUser->delete();

        return $this->backSuccess('Активация промо-кода удалена');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Store\Promo  $promo
     * @return \Illuminate\Http\Response
     */
    public function edit(Promo $promo)
    {
        return $this->view('admin.store.promo.edit', [
            'promo' => $promo
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Store\Promo  $promo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Promo $promo)
    {
        $request->validate([
            'code' => 'required|string|max:16',
            'discount' => 'required|integer|min:1|max:99'
        ]);

        $code = $request->post('code');
        if ($promo->code != $code && !is_null(Promo::findByCode($code)->first())) {
            return $this->backError('Промо-код с таким кодом уже есть')->withInput($request->post());
        }

        $promo->update([
            'code' => $code,
            'discount' => (int) $request->post('discount')
        ]);

        return $this->backSuccess('Промо-код изменен');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Store\Promo  $promo
     * @return \Illuminate\Http\Response
     */
    public function destroy(Promo $promo)
    {
        $promo->delete();

        return $this->backSuccess('Промо-код удален');
    }
}
