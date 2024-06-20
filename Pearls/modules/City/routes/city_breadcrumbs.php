<?php 

use Diglactic\Breadcrumbs\Breadcrumbs;

use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;

Breadcrumbs::for('cities', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Cities', url('cities'));
});

Breadcrumbs::for('city_create_edit', function (BreadcrumbTrail $trail) {
    $trail->parent('cities');
    $trail->push(view()->shared('title_singular'));
});