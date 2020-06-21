<?php

namespace App\Services\Dashboard;

use App\Url;
use Illuminate\Support\Str;

class AllUrlService
{
    /**
     * @codeCoverageIgnore
     */
    public function dataTable()
    {
        $url = Url::query();

        return datatables($url)
            ->editColumn('keyword', function ($url) {
                return '<span class="short_url" data-clipboard-text="'.$url->short_url.'" title="'.__('Copy to clipboard').'" data-toggle="tooltip">'.urlRemoveScheme($url->short_url).'</span>';
            })
            ->editColumn('long_url', function ($url) {
                return '
                    <span title="'.$url->meta_title.'" data-toggle="tooltip">'.Str::limit($url->meta_title, 90).'</span>
                    <br>
                    <a href="'.$url->long_url.'" target="_blank" title="'.$url->long_url.'" data-toggle="tooltip" class="text-muted">'.urlLimit($url->long_url, 70).'</a>';
            })
            ->editColumn('clicks', function ($url) {
                return '<span title="'.number_format($url->clicks).' clicks" data-toggle="tooltip">'.numberFormatShort($url->clicks).'</span>';
            })
            ->editColumn('created_at', function ($url) {
                return [
                    'display'   => '<span title="'.$url->created_at->toDayDateTimeString().'" data-toggle="tooltip">'.$url->created_at->diffForHumans().'</span>',
                    'timestamp' => $url->created_at->timestamp,
                ];
            })
            ->addColumn('created_by', function ($url) {
                return '<span>'.$url->user->name.'</span>';
            })
            ->addColumn('action', function ($url) {
                return
                    '<div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
                        <a role="button" class="btn" href="'.route('short_url.stats', $url->keyword).'" target="_blank" title="'.__('Details').'" data-toggle="tooltip"><i class="fa fa-eye"></i></a>
                        <a role="button" class="btn" href="'.route('dashboard.allurl.delete', $url->getRouteKey()).'" title="'.__('Delete').'" data-toggle="tooltip"><i class="fas fa-trash-alt"></i></a>
                    </div>';
            })
            ->rawColumns(['keyword', 'long_url', 'clicks', 'created_at.display', 'created_by', 'action'])
            ->toJson();
    }
}
