<?php


namespace App\Http\Controllers\Admin\Store;


use App\Http\Controllers\Controller;
use App\Models\Store\Purchase;

class PurchasesController extends Controller
{
    public function index()
    {
        return $this->view('admin.store.purchases.index', [
            'purchases' => Purchase::onlyCompleted()->with('user')->latest('id')->paginate(50)
        ]);
    }
}
