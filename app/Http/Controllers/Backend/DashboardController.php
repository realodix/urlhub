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
        // Counting the number of guests on the url column based on IP
        $totalGuest = DB::table('urls')
            ->select('ip', DB::raw('count(*) as total'))
            ->whereNull('user_id')
            ->groupBy('ip')
            ->get()
            ->count();

        $totalShortUrlCustom = Url::where('is_custom', 1)->count();

        return view('backend.dashboard', [
            'totalShortUrl'        => Url::count('url_key'),
            'totalShortUrlByMe'    => $this->totalShortUrlById(Auth::id()),
            'totalShortUrlByGuest' => $this->totalShortUrlById(0),
            'viewCount'            => Url::sum('views'),
            'viewCountByMe'        => $this->viewCountById(Auth::id()),
            'viewCountByGuest'     => $this->viewCountById(0),
            'totalUser'            => User::count(),
            'totalGuest'           => $totalGuest,
            'capacity'             => UrlHlp::url_key_capacity(),
            'remaining'            => UrlHlp::url_key_remaining(),
            'totalShortUrlCustom'  => $totalShortUrlCustom,

        ]);
    }

    public function getData()
    {
        $model = Url::where('user_id', Auth::id());

        return DataTables::of($model)
            ->editColumn('url_key', function ($url) {
                return '<span class="short_url" data-clipboard-text="'.$url->short_url.'" title="'.__('Copy to clipboard').'" data-toggle="tooltip">'.remove_url_schemes($url->short_url).'</span>';
            })
            ->editColumn('long_url', function ($url) {
                return '
                <span title="'.$url->meta_title.'" data-toggle="tooltip">'.str_limit($url->meta_title, 90).'</span>
                <br>
                <a href="'.$url->long_url.'" target="_blank" title="'.$url->long_url.'" data-toggle="tooltip" class="text-muted">'.url_limit($url->long_url, 70).'</a>';
            })
            ->editColumn('views', function ($url) {
                return '
                <span title="'.number_format($url->views).' views" data-toggle="tooltip">'.readable_int($url->views).'</span>';
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
            ->rawColumns(['url_key', 'long_url', 'views', 'created_at.display', 'action'])
            ->make(true);
    }

    /**
     * @param \App\Url $url
     *
     * @return \Illuminate\Routing\Redirector
     */
    public function delete(Url $url)
    {
        $this->authorize('forceDelete', $url);

        $url->delete();

        return redirect()->back();
    }

    /**
     * @param string $url_key
     */
    public function duplicate($url_key)
    {
        $url = Url::where('url_key', $url_key)
                    ->firstOrFail();

        $replicate = $url->replicate();
        $replicate->user_id = Auth::id();
        $replicate->url_key = UrlHlp::key_generator();
        $replicate->is_custom = 0;
        $replicate->views = 0;
        $replicate->save();

        return redirect()->back();
    }

    /**
     * @param int $id
     */
    public function totalShortUrlById($id)
    {
        return Url::where('user_id', $id)->count('url_key');
    }

    /**
     * @param int $id
     */
    public function viewCountById($id)
    {
        return Url::where('user_id', $id)->sum('views');
    }
}
