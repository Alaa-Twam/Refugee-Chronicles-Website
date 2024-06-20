<?php 

use Diglactic\Breadcrumbs\Breadcrumbs;

use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;

Breadcrumbs::for('chronicles', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Chronicles', url('cms/chronicles'));
});

Breadcrumbs::for('chronicle_create_edit', function (BreadcrumbTrail $trail) {
    $trail->parent('chronicles');
    $trail->push(view()->shared('title_singular'));
});