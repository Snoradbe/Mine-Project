<?php


namespace App\Http\Controllers\Status;


use App\Http\Controllers\Controller;
use App\Models\StatusReport;
use App\Services\RedisServers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function send(Request $request)
    {
        $request->validate([
            'server' => 'required|string|in:proxy,' . implode(',', RedisServers::getServers()),
            'type' => 'required|string|in:' . implode(',', config('site.status.report_types', []))
        ]);

        $cooldown = config('site.status.report_cooldown', 30);
        /* @var StatusReport $lastReport */
        $lastReport = StatusReport::where('user_id', $this->user()->id)->latest('id')->take(1)->first();
        if (!is_null($lastReport) && Carbon::now()->getTimestamp() - $lastReport->created_at->getTimestamp() < $cooldown) {
            return new JsonResponse([
                'message' => __('status.reports.responses.cooldown', ['cooldown' => $cooldown])
            ], 500);
        }

        $report = new StatusReport([
            'user_id' => $this->user()->id,
            'servername' => $request->post('server'),
            'type' => $request->post('type')
        ]);
        $report->save();

        return new JsonResponse(['message' => __('status.reports.responses.ok')]);
    }
}
