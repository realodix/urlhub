<?php

Breadcrumbs::for('admin', function ($trail) {
    $trail->push(__('Dashboard'), route('admin'));
});

Breadcrumbs::for('admin.allurl', function ($trail) {
    $trail->parent('admin');
    $trail->push('All URLs', route('admin.allurl'));
});

Breadcrumbs::for('viewProfile', function ($trail) {
    $trail->parent('admin');
    $trail->push('Profile', route('viewProfile'));
});

Breadcrumbs::for('viewChangePassword', function ($trail) {
    $trail->parent('admin');
    $trail->push('Change Password', route('viewChangePassword'));
});
