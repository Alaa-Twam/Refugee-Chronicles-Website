<?php 

use Diglactic\Breadcrumbs\Breadcrumbs;

use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;

// Dashboard
Breadcrumbs::for('dashboard', function (BreadcrumbTrail $trail) {
    $trail->push('Dashboard', url('dashboard'));
});

// Profile
Breadcrumbs::for('profile', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Profile', url('profile'));
});

// Users
Breadcrumbs::for('users', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('User', url('users'));
});

Breadcrumbs::for('user_create_edit', function (BreadcrumbTrail $trail) {
    $trail->parent('users');
    $trail->push(view()->shared('title_singular'));
});

Breadcrumbs::for('user_show', function (BreadcrumbTrail $trail) {
    $trail->parent('users');
    $trail->push(view()->shared('title_singular'));
});

// Roles
Breadcrumbs::for('roles', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Roles', url('roles'));
});

Breadcrumbs::for('role_create_edit', function (BreadcrumbTrail $trail) {
    $trail->parent('roles');
    $trail->push(view()->shared('title_singular'));
});