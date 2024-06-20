<?php 

use Diglactic\Breadcrumbs\Breadcrumbs;

use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;

Breadcrumbs::for('sliders', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Sliders', url('sliders'));
});

Breadcrumbs::for('slider_create_edit', function (BreadcrumbTrail $trail) {
    $trail->parent('sliders');
    $trail->push(view()->shared('title_singular'));
});