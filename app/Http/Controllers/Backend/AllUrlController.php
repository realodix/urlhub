<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Url;
use Vinkla\Hashids\Facades\Hashids;
use Yajra\Datatables\Datatables;

class AllUrlController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:admin');
    }

    public function index()
    {
        return view('backend.all-url');
    }

    public function getData()
    {
        $model = Url::query();

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
            ->addColumn('created_by', function ($url) {
                if (isset($url->user->name)) {
                    return '<span>'.$url->user->name.'</span>';
                } else {
                    return '<span>Guest</span>';
                }
            })
            ->addColumn('action', function ($url) {
                return
                '<div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
                    <a role="button" class="btn" href="'.route('short_url.statics', $url->short_url).'" target="_blank" title="'.__('Details').'" data-toggle="tooltip"><i class="fa fa-eye"></i></a>
                    <a role="button" class="btn" href="'.route('admin.allurl.delete', $url->id).'" title="'.__('Delete').'" data-toggle="tooltip"><i class="fas fa-trash-alt"></i></a>
                 </div>';
            })
            ->rawColumns(['short_url', 'long_url', 'views', 'created_at.display', 'created_by', 'action'])
            ->make(true);
    }

    public function delete($id)
    {
        Url::destroy(Hashids::decode($id));

        return redirect()->back();
    }
}
