<?php

namespace Pearls\Modules\City\Http\Controllers;

use Pearls\Base\Http\Controllers\BaseController;
use Pearls\Modules\City\DataTables\CitiesDataTable;
use Pearls\Modules\City\Http\Requests\CityRequest;
use Pearls\Modules\City\Models\City;

class CityController extends BaseController
{
    protected $breadcrumb = 'cities';

    public function __construct()
    {
        $this->resource_url = config('city.models.city.resource_url');
        $this->title = 'Cities';
        $this->title_singular = 'City';
        parent::__construct();
    }

    public function index(CityRequest $request, CitiesDataTable $dataTable)
    {
        $showCreateButton = false;

        $this->setViewSharedData(['breadcrumb' => $this->breadcrumb]);
        
        return $dataTable->render('City::cities.index', compact('showCreateButton'));
    }

    public function edit(CityRequest $request, City $city)
    {
        $this->setViewSharedData([
            'title_singular' => "Update [{$city->name}]",
            'breadcrumb' => \Str::singular($this->breadcrumb) . '_create_edit'
        ]);

        return view('City::cities.create_edit')->with(compact('city'));
    }

    public function update(CityRequest $request, city $city)
    {

        try {
            $city_data = $request->all();

            $city->update($city_data);

            flash(trans('Pearls::messages.success.updated', ['item' => ucfirst($this->title_singular)]))->success();
        } catch (\Exception $exception) {
            logger($exception->getMessage());
        }

        return redirectTo($this->resource_url);
    }
}