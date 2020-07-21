<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Url;
use App\Models\User;
use App\Services\KeyService;
use App\Services\UrlService;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class DashboardController extends Controller
{
    /**
     * DashboardController constructor.
     *
     * @param UrlService $urlSrvc
     */
    public function __construct(UrlService $urlSrvc)
    {
        $this->urlSrvc = $urlSrvc;
    }

    /**
     * Show all user short URLs.
     */
    public function view()
    {
        $userSrvc = new UserService;
        $keySrvc = new KeyService;

        return view('backend.dashboard', [
            'shortUrlCount'        => $this->urlSrvc->shortUrlCount(),
            'shortUrlCountByMe'    => $this->urlSrvc->shortUrlCountOwnedBy(Auth::id()),
            'shortUrlCountByGuest' => $this->urlSrvc->shortUrlCountOwnedBy(),
            'clickCount'           => $this->urlSrvc->clickCount(),
            'clickCountFromMe'     => $this->urlSrvc->clickCountOwnedBy(Auth::id()),
            'clickCountFromGuest'  => $this->urlSrvc->clickCountOwnedBy(),
            'userCount'            => $userSrvc->userCount(),
            'guestCount'           => $userSrvc->guestCount(),
            'keyCapacity'          => $keySrvc->keyCapacity(),
            'keyRemaining'         => $keySrvc->keyRemaining(),
            'remainingPercentage'  => remainingPercentage($keySrvc->numberOfUsedKey(), $keySrvc->keyCapacity()),
        ]);
    }

    /**
     * Show the long url edit page.
     *
     * @param string $key
     */
    public function edit($key)
    {
        $url = Url::whereKeyword($key)->firstOrFail();

        $this->authorize('updateUrl', $url);

        return view('backend.edit', compact('url'));
    }

    /**
     * Update the long url that was previously set to the new long url.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Url                 $url
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(Request $request, Url $url)
    {
        $this->urlSrvc->update($request->only('long_url', 'meta_title'), $url);

        return redirect()->route('dashboard')
                         ->withFlashSuccess(__('Link changed successfully !'));
    }

    /**
     * Delete a shortened URL on user request.
     *
     * @param \App\Models\Url $url
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function delete(Url $url)
    {
        $this->authorize('forceDelete', $url);

        $this->urlSrvc->delete($url);

        return redirect()->back()
                         ->withFlashSuccess(__('Link was successfully deleted.'));
    }

    /**
     * UrlHub only allows users (registered & unregistered) to have a unique
     * link. You can duplicate it and it will produce a new unique random key.
     *
     * @param string $key
     */
    public function duplicate($key)
    {
        $this->urlSrvc->duplicate($key, Auth::id());

        return redirect()->back()
                         ->withFlashSuccess(__('Link was successfully duplicated.'));
    }

    /**
     * @codeCoverageIgnore
     */
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
