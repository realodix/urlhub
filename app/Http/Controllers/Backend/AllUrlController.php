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
                        return '<a href="'.url('/'.$url->short_url).'" target="_blank">'.urlToDomain(url('/'.$url->short_url)).'</a>';
                    } else {
                        return '<a href="'.url('/'.$url->short_url_custom).'" target="_blank">'.urlToDomain(url('/'.$url->short_url_custom)).'</a>';
                    }
                })
                ->editColumn('long_url', function ($url) {
                    return '<a href="'.$url->long_url.'" target="_blank">'.$url->long_url_mod.'</a>';
                })
                ->editColumn('created_at', function ($url) {
                    return
                    '<span title="'.$url->created_at.'">'.$url->created_at->diffForHumans().'</span>';
                })
                ->addColumn('author', function ($url) {
                    if (isset($url->user->name)) {
                        return $url->user->name;
                    } else {
                        return 'Guest';
                    }
                })
                ->addColumn('action', function ($url) {
                    return
                    '<div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
                        <a role="button" class="btn" href="'.route('short_url.statics', $url->short_url).'" target="_blank" title="'.__('Details').'"><i class="fa fa-eye"></i></a>
                        <a role="button" class="btn text-danger" href="'.route('admin.allurl.delete', $url->id).'" title="'.__('Delete').'"><i class="fas fa-trash-alt"></i></a>
                     </div>';
                })
                ->rawColumns(['short_url', 'long_url', 'created_at', 'author', 'action'])
                ->make(true);
    }

    public function delete($id)
    {
        Url::destroy($id);

        return redirect()->back();
    }
}
