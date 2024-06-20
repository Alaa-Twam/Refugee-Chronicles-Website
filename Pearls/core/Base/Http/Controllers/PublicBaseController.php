<?php

namespace Pearls\Base\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PublicBaseController extends BaseController
{
    /**
     * PublicBaseController constructor.
     */
    public function __construct()
    {
        $this->pearls_middleware = [];
        $this->pearls_middleware_except = [];
        parent::__construct();
    }
}