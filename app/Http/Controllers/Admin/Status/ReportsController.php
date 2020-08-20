<?php


namespace App\Http\Controllers\Admin\Status;


use App\Http\Controllers\Controller;
use App\Models\StatusReport;
use Illuminate\Support\Carbon;

class ReportsController extends Controller
{
    public function index()
    {
        Carbon::setLocale('ru');
        return $this->view('admin.status.reports', [
            'reports' => StatusReport::with('user')->latest('id')->paginate(100),
            'now' => time()
        ]);
    }
}
