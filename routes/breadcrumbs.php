<?php

use Tabuna\Breadcrumbs\Breadcrumbs;

Breadcrumbs::for('dashboard', function ($trail) {
    $trail->push(__('Dashboard'), route('dashboard'));
});

Breadcrumbs::for('short_url.edit', function ($trail, $keyword) {
    $trail->parent('dashboard')
          ->push($keyword, route('short_url.edit', $keyword));
});

Breadcrumbs::for('dashboard.allurl', function ($trail) {
    $trail->parent('dashboard')
          ->push(__('All URLs'), route('dashboard.allurl'));
});

Breadcrumbs::for('user.index', function ($trail) {
    $trail->parent('dashboard')
          ->push(__('All Users'), route('user.index'));
});

Breadcrumbs::for('user.edit', function ($trail, $user) {
    $trail->parent('user.index')
          ->push(__('Profile'), route('user.edit', $user));
});

Breadcrumbs::for('user.change-password', function ($trail, $user) {
    $trail->parent('user.edit', $user->name)
          ->push(__('Change Password'), route('user.change-password', $user));
});

Breadcrumbs::for('dashboard.stat', function ($trail) {
    $trail->parent('dashboard')
          ->push(__('Statistics'), route('dashboard.stat'));
});
