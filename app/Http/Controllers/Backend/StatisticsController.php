<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Url;
use App\User;
use Illuminate\Support\Facades\Auth;
use Yajra\Datatables\Datatables;

class StatisticsController extends Controller
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

        return view('backend.statistics');
    }
}
