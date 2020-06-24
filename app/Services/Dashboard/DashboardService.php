<?php

namespace App\Services\Dashboard;

use App\Url;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

/**
 * @codeCoverageIgnore
 */
class DashboardService
{
    /**
     * @param array     $request
     * @param string    $url
     */
    public function update($data, $url)
    {
        $url->long_url = $data['long_url'];
        $url->meta_title = $data['meta_title'];
        $url->save();
    }

    /**
     * @param string $key
     */
    public function duplicate($key, $authId)
    {
        $url = new Url;
        $shortenedUrl = Url::whereKeyword($key)->firstOrFail();

        $replicate = $shortenedUrl->replicate()->fill([
            'user_id'   => $authId,
            'keyword'   => $url->randomKeyGenerator(),
            'is_custom' => 0,
            'clicks'    => 0,
        ]);
        $replicate->save();
    }

    public function dataTable()
    {
        $urlModel = Url::whereUserId(Auth::id());

        return datatables($urlModel)
            ->editColumn('keyword', function (Url $url) {
                return '<span class="short_url" data-clipboard-text="'.$url->short_url.'" title="'.__('Copy to clipboard').'" data-toggle="tooltip">'.urlRemoveScheme($url->short_url).'</span>';
            })
            ->editColumn('long_url', function (Url $url) {
                return '
                    <span title="'.$url->meta_title.'" data-toggle="tooltip">'.Str::limit($url->meta_title, 90).'</span>
                    <br>
                    <a href="'.$url->long_url.'" target="_blank" title="'.$url->long_url.'" data-toggle="tooltip" class="text-muted">'.urlLimit($url->long_url, 70).'</a>';
            })
            ->editColumn('clicks', function (Url $url) {
                return '<span title="'.number_format($url->clicks).' clicks" data-toggle="tooltip">'.numberFormatShort($url->clicks).'</span>';
            })
            ->editColumn('created_at', function (Url $url) {
                return [
                    'display'   => '<span title="'.$url->created_at->toDayDateTimeString().'" data-toggle="tooltip">'.$url->created_at->diffForHumans().'</span>',
                    'timestamp' => $url->created_at->timestamp,
                ];
            })
            ->addColumn('action', function (Url $url) {
                return
                    '<div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
                        <a role="button" class="btn" href="'.route('short_url.stats', $url->keyword).'" target="_blank" title="'.__('Details').'" data-toggle="tooltip"><i class="fa fa-eye"></i></a>
                        <a role="button" class="btn" href="'.route('dashboard.duplicate', $url->keyword).'" title="'.__('Duplicate').'" data-toggle="tooltip"><i class="far fa-clone"></i></a>
                        <a role="button" class="btn" href="'.route('short_url.edit', $url->keyword).'" title="'.__('Edit').'" data-toggle="tooltip"><i class="fas fa-edit"></i></a>
                        <a role="button" class="btn" href="'.route('dashboard.delete', $url->getRouteKey()).'" title="'.__('Delete').'" data-toggle="tooltip"><i class="fas fa-trash-alt"></i></a>
                    </div>';
            })
            ->rawColumns(['keyword', 'long_url', 'clicks', 'created_at.display', 'action'])
            ->toJson();
    }
}
