<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Url;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Yajra\Datatables\Datatables;

class DashboardController extends Controller
{
    /**
     * @var url
     */
    protected $url;

    /**
     * Url constructor.
     *
     * @param Url $url
     */
    public function __construct(Url $url)
    {
        $this->url = $url;
    }

    /**
     * Show users all their Short URLs.
     */
    public function view()
    {
        $user = new User;

        return view('backend.dashboard', [
            'totalShortUrl'        => $this->url->totalShortUrl(),
            'totalShortUrlByMe'    => $this->url->totalShortUrlById(Auth::id()),
            'totalShortUrlByGuest' => $this->url->totalShortUrlById(),
            'totalClicks'          => $this->url->totalClicks(),
            'totalClicksByMe'      => $this->url->totalClicksById(Auth::id()),
            'totalClicksByGuest'   => $this->url->totalClicksById(),
            'totalUser'            => $user->totalUser(),
            'totalGuest'           => $user->totalGuest(),
            'capacity'             => $this->url->url_key_capacity(),
            'remaining'            => $this->url->url_key_remaining(),

        ]);
    }

    /**
     * @codeCoverageIgnore
     */
    public function getData()
    {
        $model = Url::whereUserId(Auth::id());

        return DataTables::of($model)
            ->editColumn('url_key', function ($url) {
                return '<span class="short_url" data-clipboard-text="'.$url->short_url.'" title="'.__('Copy to clipboard').'" data-toggle="tooltip">'.remove_schemes($url->short_url).'</span>';
            })
            ->editColumn('long_url', function ($url) {
                return '
                <span title="'.$url->meta_title.'" data-toggle="tooltip">'.Str::limit($url->meta_title, 90).'</span>
                <br>
                <a href="'.$url->long_url.'" target="_blank" title="'.$url->long_url.'" data-toggle="tooltip" class="text-muted">'.url_limit($url->long_url, 70).'</a>';
            })
            ->editColumn('clicks', function ($url) {
                return '
                <span title="'.number_format($url->clicks).' clicks" data-toggle="tooltip">'.number_format_short($url->clicks).'</span>';
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
                    <a role="button" class="btn" href="'.route('dashboard.duplicate', $url->url_key).'" title="'.__('Duplicate').'" data-toggle="tooltip"><i class="far fa-clone"></i></a>
                    <a role="button" class="btn" href="'.route('dashboard.delete', $url->getRouteKey()).'" title="'.__('Delete').'" data-toggle="tooltip"><i class="fas fa-trash-alt"></i></a>
                 </div>';
            })
            ->rawColumns(['url_key', 'long_url', 'clicks', 'created_at.display', 'action'])
            ->toJson();
    }

    /**
     * Delete a Short URL on user request.
     *
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
     * Defaultly UrlHub only permited only one link at the time,
     * but you can duplicate it.
     *
     * @param string $url_key
     * @return \Illuminate\Http\RedirectResponse
     */
    public function duplicate($url_key)
    {
        $url = Url::whereUrlKey($url_key)->firstOrFail();

        $replicate = $url->replicate();
        $replicate->user_id = Auth::id();
        $replicate->url_key = $this->url->key_generator();
        $replicate->is_custom = 0;
        $replicate->clicks = 0;
        $replicate->save();

        return redirect()->back()
                         ->withFlashSuccess(__('Link was successfully duplicated.'));
    }
}
