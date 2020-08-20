<?php


namespace App\Http\Controllers\Status;


use App\Http\Controllers\Controller;
use App\Models\Server;

class IndexController extends Controller
{
    public function index()
    {
        return $this->view('status.index', [
            'types' => [
                'SERVICE' => Server::TYPE_SERVICE,
                'SERVICE_PRIMARY' => Server::TYPE_SERVICE_PRIMARY,
                'SERVICE_SECONDARY' => Server::TYPE_SERVICE_SECONDARY,
                'GAME' => Server::TYPE_GAME,
            ],
            'lang' => [
                'status' => __('status'),
                'words' => __('words'),
            ],
			'domain' => config('site.domain'),
            'settings' => [
                'report_types' => config('site.status.report_types', []),
                'reports_url' => route('status.reports.send'),
            ]
        ]);
    }
}
