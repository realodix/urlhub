<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Settings\GeneralSettings;
use Illuminate\Routing\Controllers\{HasMiddleware, Middleware};

class SettingController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [new Middleware('role:admin')];
    }

    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function view(GeneralSettings $settings)
    {
        return view('backend.settings', ['settings' => $settings]);
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(GeneralSettings $settings)
    {
        $settings->update();

        return redirect()->back()
            ->with('flash_success', __('Settings updated.'));
    }
}
