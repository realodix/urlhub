<?php

Breadcrumbs::for('admin', function ($trail) {
    $trail->push(__('Dashboard'), route('admin'));
});

Breadcrumbs::for('admin.allurl', function ($trail) {
    $trail->parent('admin');
    $trail->push('All URLs', route('admin.allurl'));
});

Breadcrumbs::for('showChangePassword', function ($trail) {
    $trail->parent('admin');
    $trail->push('All URLs', route('showChangePassword'));
});
