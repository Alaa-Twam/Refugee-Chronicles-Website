<?php

namespace Pearls\User\Http\Controllers;

use Pearls\Base\Http\Controllers\BaseController;
use Illuminate\Http\Request;

class DashboardController extends BaseController
{
    protected $breadcrumb = 'dashboard';

    public function __construct()
    {
        $this->resource_url = 'dashboard';
        $this->title = 'Dashboard';
        $this->title_singular = 'Dashboard';

        parent::__construct();
    }

    public function index()
    {
        $this->setViewSharedData(['breadcrumb' => $this->breadcrumb]);

        return view('User::dashboard');
    }
}