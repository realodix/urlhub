<?php

Breadcrumbs::for('admin', function ($trail) {
    $trail->push(__('Dashboard'), route('admin'));
});

Breadcrumbs::for('admin.allurl', function ($trail) {
    $trail->parent('admin');
    $trail->push(__('All URLs'), route('admin.allurl'));
});

Breadcrumbs::for('user.index', function ($trail) {
    $trail->parent('admin');
    $trail->push(__('All Users'), route('user.index'));
});

Breadcrumbs::for('user.edit', function ($trail, $user) {
    $trail->parent('user.index');
    $trail->push(__('Profile'), route('user.edit', $user));
});

Breadcrumbs::for('user.change-password', function ($trail, $user) {
    $trail->parent('user.edit', $user);
    $trail->push(__('Change Password'), route('user.change-password', $user));
});
