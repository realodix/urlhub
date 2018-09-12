<?php

Breadcrumbs::for('admin', function ($trail) {
    $trail->push(__('Dashboard'), route('admin'));
});

Breadcrumbs::for('admin.allurl', function ($trail) {
    $trail->parent('admin');
    $trail->push('All URLs', route('admin.allurl'));
});

Breadcrumbs::for('user.index', function ($trail) {
    $trail->parent('admin');
    $trail->push('All Users', route('user.index'));
});

Breadcrumbs::for('user.edit', function ($trail, $user) {
    $trail->parent('admin');
    $trail->push('Profile', route('user.edit', $user));
});

Breadcrumbs::for('user.change-password', function ($trail, $user) {
    $trail->parent('admin');
    $trail->push('Change Password', route('user.change-password', $user));
});
