<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Url;
use App\User;
use Facades\App\Helpers\UrlHlp;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Datatables;

class DashboardController extends Controller
{
    public function view()
    {
        // Count the number of guests in the url column based on IP
        // and grouped by ip.
        $totalGuest = Url::select('ip', DB::raw('count(*) as total'))
                         ->whereNull('user_id')
                         ->groupBy('ip')
                         ->get()
                         ->count();

        return view('backend.dashboard', [
            'totalShortUrl'        => Url::count('url_key'),
            'totalShortUrlByMe'    => $this->totalShortUrlById(Auth::id()),
            'totalShortUrlByGuest' => $this->totalShortUrlById(),
            'totalClicks'          => Url::sum('clicks'),
            'totalClicksByMe'      => $this->totalClicksById(Auth::id()),
            'totalClicksByGuest'   => $this->totalClicksById(),
            'totalUser'            => User::count(),
            'totalGuest'           => $totalGuest,
            'capacity'             => UrlHlp::url_key_capacity(),
            'remaining'            => UrlHlp::url_key_remaining(),

        ]);
    }

    public function getData()
    {
        $model = Url::whereUserId(Auth::id());

        return DataTables::of($model)
            ->editColumn('url_key', function ($url) {
                return '<span class="short_url" data-clipboard-text="'.$url->short_url.'" title="'.__('Copy to clipboard').'" data-toggle="tooltip">'.remove_schemes($url->short_url).'</span>';
            })
            ->editColumn('long_url', function ($url) {
                return '
                <span title="'.$url->meta_title.'" data-toggle="tooltip">'.str_limit($url->meta_title, 90).'</span>
                <br>
                <a href="'.$url->long_url.'" target="_blank" title="'.$url->long_url.'" data-toggle="tooltip" class="text-muted">'.url_limit($url->long_url, 70).'</a>';
            })
            ->editColumn('clicks', function ($url) {
                return '
                <span title="'.number_format($url->clicks).' clicks" data-toggle="tooltip">'.readable_int($url->clicks).'</span>';
            })
            ->editColumn('created_at', function ($url) {
                return [
                    'display'   => '<span title="'.$url->created_at->toDayDateTimeString().'" data-toggle="tooltip">'.$url->created_at->diffForHumans().'</span>',
                    'timestamp' => $url->created_at->timestamp,
                ];
            })
            ->addColumn('action', function ($url) {
                return
                '<div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
                    <a role="button" class="btn" href="'.route('short_url.stats', $url->url_key).'" target="_blank" title="'.__('Details').'" data-toggle="tooltip"><i class="fa fa-eye"></i></a>
                    <a role="button" class="btn" href="'.route('admin.duplicate', $url->url_key).'" title="'.__('Duplicate').'" data-toggle="tooltip"><i class="far fa-clone"></i></a>
                    <a role="button" class="btn" href="'.route('admin.delete', $url->getRouteKey()).'" title="'.__('Delete').'" data-toggle="tooltip"><i class="fas fa-trash-alt"></i></a>
                 </div>';
            })
            ->rawColumns(['url_key', 'long_url', 'clicks', 'created_at.display', 'action'])
            ->toJson();
    }

    /**
     * @param \App\Url $url
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function delete(Url $url)
    {
        $this->authorize('forceDelete', $url);

        $url->delete();

        return redirect()->back()
                         ->withFlashSuccess(__('Link was successfully deleted.'));
    }

    /**
     * @param string $url_key
     * @return \Illuminate\Http\RedirectResponse
     */
    public function duplicate($url_key)
    {
        $url = Url::whereUrlKey($url_key)
                  ->firstOrFail();

        $replicate = $url->replicate();
        $replicate->user_id = Auth::id();
        $replicate->url_key = UrlHlp::key_generator();
        $replicate->is_custom = 0;
        $replicate->clicks = 0;
        $replicate->save();

        return redirect()->back()
                         ->withFlashSuccess(__('Link was successfully duplicated.'));
    }

    /**
     * @param int $id
     */
    public function totalShortUrlById($id = null)
    {
        return Url::whereUserId($id)->count('url_key');
    }

    /**
     * @param int $id
     */
    public function totalClicksById($id = null)
    {
        return Url::whereUserId($id)->sum('clicks');
    }
}
