<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Url;
use App\Services\UrlService;
use Illuminate\Support\Str;

class AllUrlController extends Controller
{
    /**
     * AllUrlController constructor.
     *
     * @param  UrlService  $urlSrvc  \App\Services\UrlService
     */
    public function __construct(protected UrlService $urlSrvc)
    {
        $this->middleware('role:admin');
    }

    /**
     * Show all short URLs created by all users.
     */
    public function view()
    {
        return view('backend.all-url');
    }

    /**
     * Delete a Short URL on user (Admin) request.
     *
     * @param  mixed  $url
     */
    public function delete($url)
    {
        $this->urlSrvc->delete($url);

        return redirect()->back()
                         ->withFlashSuccess(__('Link was successfully deleted.'));
    }

    /**
     * @return string JSON
     * @codeCoverageIgnore
     */
    public function dataTable()
    {
        $urlModel = Url::query();

        return datatables($urlModel)
            ->editColumn('keyword', function (Url $url) {
                return '<span class="short_url" data-clipboard-text="'.$url->short_url.'" title="'.__('Copy to clipboard').'" data-toggle="tooltip">'.urlDisplay($url->short_url, false).'</span>';
            })
            ->editColumn('long_url', function (Url $url) {
                return '
                    <span title="'.$url->meta_title.'" data-toggle="tooltip">
                        '.Str::limit($url->meta_title, 80).'
                    </span>
                    <br>
                    <a href="'.$url->long_url.'" target="_blank" title="'.$url->long_url.'" data-toggle="tooltip" class="text-muted">
                        '.urlDisplay($url->long_url, false, 70).'
                    </a>';
            })
            ->editColumn('clicks', function (Url $url) {
                return '<span title="'.number_format($url->clicks).' clicks" data-toggle="tooltip">'.numberToAmountShort($url->clicks).'</span>';
            })
            ->editColumn('created_at', function (Url $url) {
                return [
                    'display'   => '<span title="'.$url->created_at->toDayDateTimeString().'" data-toggle="tooltip">'.$url->created_at->diffForHumans().'</span>',
                    'timestamp' => $url->created_at->timestamp,
                ];
            })
            ->addColumn('created_by', function (Url $url) {
                return '<span>'.$url->user->name.'</span>';
            })
            ->addColumn('action', function (Url $url) {
                return '<div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
                           <a role="button" class="btn" href="'.route('short_url.stats', $url->keyword).'" target="_blank" title="'.__('Details').'" data-toggle="tooltip"><i class="fa fa-eye"></i></a>
                           <a role="button" class="btn" href="'.route('dashboard.allurl.delete', $url->getRouteKey()).'" title="'.__('Delete').'" data-toggle="tooltip"><i class="fas fa-trash-alt"></i></a>
                       </div>';
            })
            ->rawColumns(['keyword', 'long_url', 'clicks', 'created_at.display', 'created_by', 'action'])
            ->toJson();
    }
}
