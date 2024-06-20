<?php

namespace Pearls\Base\Http\Controllers;

use App\Http\Controllers\Controller;

class BaseController extends Controller
{
    public $resource_url = '';
    public $title = '';
    public $title_singular = '';
    protected $pearls_middleware = ['auth'];
    protected $pearls_middleware_except = [];

    /**
     * BaseController constructor.
     */
    public function __construct()
    {
        $this->middleware($this->pearls_middleware, ['except' => $this->pearls_middleware_except]);

        $this->setViewSharedData();
    }

    /**
     * set variables shared with all controller views
     * @param array $variables
     */
    protected function setViewSharedData($variables = [])
    {
        if (\Arr::has($variables, 'title_singular')) {
            $this->title_singular = \Arr::get($variables, 'title_singular');
        }
        if (\Arr::has($variables, 'title')) {
            $this->title = \Arr::get($variables, 'title');
        }
        if (\Arr::has($variables, 'resource_url')) {
            $this->resource_url = \Arr::get($variables, 'resource_url');
        }

        view()->share(array_merge([
            'title' => $this->title,
            'title_singular' => $this->title_singular,
            'resource_url' => $this->resource_url
        ], $variables));
    }
}
