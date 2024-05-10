<?php

use App\Models\Post;
use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;

Breadcrumbs::for('home', function (BreadcrumbTrail $trail) {
    $trail->push('Home', route('home'));
});

Breadcrumbs::for('getCustomers', function (BreadcrumbTrail $trail) {
    $trail->push('Customers', route('getCustomers'));
});

Breadcrumbs::for('getOrders', function (BreadcrumbTrail $trail) {
    $trail->push('Orders', route('getOrders'));
});

Breadcrumbs::for('calculator', function (BreadcrumbTrail $trail) {
    $trail->push('Calculator', route('calculator'));
});