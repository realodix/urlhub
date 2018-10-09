<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Url;
use App\User;
use Illuminate\Support\Facades\Auth;
use Yajra\Datatables\Datatables;

class DashboardController extends Controller
{
    public function index()
    {
        return view('backend.dashboard', [
            'totalShortUrl'        => $this->totalShortUrl(),
            'totalShortUrlById'    => $this->totalShortUrlById(),
            'totalShortUrlByGuest' => $this->totalShortUrlByGuest(),
            'viewCount'            => $this->viewCount(),
            'viewCountById'        => $this->viewCountById(),
            'viewCountByGuest'     => $this->viewCountByGuest(),
            'userCount'            => $this->userCount(),
        ]);
    }

    public function getData()
    {
        $model = Url::where('user_id', Auth::id());

        return DataTables::of($model)
            ->editColumn('short_url', function ($url) {
                if ($url->short_url_custom == false) {
                    return '<span class="short_url" data-clipboard-text="'.url('/'.$url->short_url).'" title="Copy to clipboard" data-toggle="tooltip">'.url_parsed(url('/'.$url->short_url)).'</span>';
                } else {
                    return '<span class="short_url" data-clipboard-text="'.url('/'.$url->short_url_custom).'" title="Copy to clipboard" data-toggle="tooltip">'.url_parsed(url('/'.$url->short_url_custom)).'</span>';
                }
            })
            ->editColumn('long_url', function ($url) {
                return '
                <span title="'.$url->long_url_title.'" data-toggle="tooltip">'.str_limit($url->long_url_title, 90).'</span>
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
                    <a role="button" class="btn" href="'.route('short_url.statics', $url->short_url).'" target="_blank" title="'.__('Details').'" data-toggle="tooltip"><i class="fa fa-eye"></i></a>
                    <a role="button" class="btn" href="'.route('admin.allurl.delete', $url->id).'" title="'.__('Delete').'" data-toggle="tooltip"><i class="fas fa-trash-alt"></i></a>
                 </div>';
            })
            ->rawColumns(['short_url', 'long_url', 'views', 'created_at.display', 'action'])
            ->make(true);
    }

    public function delete($id)
    {
        Url::destroy($id);

        return redirect()->back();
    }

    public function totalShortUrl()
    {
        return Url::count('short_url');
    }

    public function totalShortUrlById()
    {
        return Url::where('user_id', Auth::id())->count('short_url');
    }

    public function totalShortUrlByGuest()
    {
        return Url::where('user_id', 0)->count('short_url');
    }

    public function viewCount()
    {
        return Url::sum('views');
    }

    public function viewCountById()
    {
        return Url::where('user_id', Auth::id())->sum('views');
    }

    public function viewCountByGuest()
    {
        return Url::where('user_id', 0)->sum('views');
    }

    public function userCount()
    {
        return User::count();
    }
}
