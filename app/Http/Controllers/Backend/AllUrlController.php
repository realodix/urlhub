<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Url;
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
                    return '<span class="short_url" data-clipboard-text="'.url('/'.$url->short_url).'" title="Copy to clipboard" data-toggle="tooltip">'.url_normalize(url('/'.$url->short_url)).'</span>';
                } else {
                    return '<span class="short_url" data-clipboard-text="'.url('/'.$url->short_url_custom).'" title="Copy to clipboard" data-toggle="tooltip">'.url_normalize(url('/'.$url->short_url_custom)).'</span>';
                }
            })
            ->editColumn('long_url', function ($url) {
                return '
                    <span title="'.$url->long_url_title.'" data-toggle="tooltip">'.str_limit($url->long_url_title, 90).'</span>
                    <br>
                    <a href="'.$url->long_url.'" target="_blank" title="'.$url->long_url.'" data-toggle="tooltip" class="text-muted">'.url_limit($url->long_url, 70).'</a>';
            })
            ->editColumn('created_at', function ($url) {
                return [
                    'display'   => '<span title="'.$url->created_at->toDayDateTimeString().'" data-toggle="tooltip" style="cursor: default;">'.$url->created_at->diffForHumans().'</span>',
                    'timestamp' => $url->created_at->timestamp,
                ];
            })
            ->addColumn('author', function ($url) {
                if (isset($url->user->name)) {
                    return '<span style="cursor: default;">'.$url->user->name.'</span>';
                } else {
                    return '<span style="cursor: default;">Guest</span>';
                }
            })
            ->addColumn('action', function ($url) {
                return
                '<div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
                    <a role="button" class="btn" href="'.route('short_url.statics', $url->short_url).'" target="_blank" title="'.__('Details').'" data-toggle="tooltip"><i class="fa fa-eye"></i></a>
                    <a role="button" class="btn" href="'.route('admin.allurl.delete', $url->id).'" title="'.__('Delete').'" data-toggle="tooltip"><i class="fas fa-trash-alt"></i></a>
                 </div>';
            })
            ->rawColumns(['short_url', 'long_url', 'created_at.display', 'author', 'action'])
            ->make(true);
    }

    public function delete($id)
    {
        Url::destroy($id);

        return redirect()->back();
    }
}
