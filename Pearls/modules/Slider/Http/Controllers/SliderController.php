<?php

namespace Pearls\Modules\Slider\Http\Controllers;

use Pearls\Base\Http\Controllers\BaseController;
use Pearls\Modules\Slider\DataTables\SlidersDataTable;
use Pearls\Modules\Slider\Http\Requests\SliderRequest;
use Pearls\Modules\Slider\Models\Slider;

class SliderController extends BaseController
{
    protected $breadcrumb = 'sliders';

    public function __construct()
    {
        $this->resource_url = config('slider.models.slider.resource_url');
        $this->title = 'Sliders';
        $this->title_singular = 'Slider';
        parent::__construct();
    }

    public function index(SliderRequest $request, SlidersDataTable $dataTable)
    {
        $this->setViewSharedData(['breadcrumb' => $this->breadcrumb]);
        
        return $dataTable->render('Slider::sliders.index');
    }

    public function create(SliderRequest $request)
    {
        $slider = new Slider();

        $this->setViewSharedData([
            'title_singular' => 'Create ' . $this->title_singular,
            'breadcrumb' => \Str::singular($this->breadcrumb) . '_create_edit'
        ]);

        return view('Slider::sliders.create_edit')->with(compact('slider'));
    }

    public function store(SliderRequest $request)
    {
        try {
            $slider_data = $request->except('image');

            $slider = Slider::create($slider_data);

            if (isset($request->image)) {
                $prefix = \Str::slug(class_basename($slider));
                $extension = $request->file('image')->getClientOriginalExtension();
                $newFileName = time() . '-' . $slider->id . '.' . $extension;

                $slider->addMediaFromRequest('image')
                    ->usingName($newFileName)
                    ->usingFileName($newFileName)
                    ->withCustomProperties(['root' => $prefix . '-' . $slider->id])
                    ->toMediaCollection($prefix . '-media', 'media');
            }

            flash(trans('Pearls::messages.success.created', ['item' => ucfirst($this->title_singular)]))->success();
        } catch (\Exception $exception) {
            log_exception($exception, Slider::class, 'store');
        }

        return redirectTo($this->resource_url);
    }

    public function edit(SliderRequest $request, Slider $slider)
    {
        $this->setViewSharedData([
            'title_singular' => "Update [{$slider->name}]",
            'breadcrumb' => \Str::singular($this->breadcrumb) . '_create_edit'
        ]);

        return view('Slider::sliders.create_edit')->with(compact('slider'));
    }

    public function update(SliderRequest $request, Slider $slider)
    {

        try {
            $slider_data = $request->except('image');

            $slider->update($slider_data);

            if (isset($request->image)) {
                $prefix = \Str::slug(class_basename($slider));
                $extension = $request->file('image')->getClientOriginalExtension();
                $newFileName = time() . '-' . $slider->id . '.' . $extension;
                
                $slider->clearMediaCollection($prefix . '-media');
                $slider->addMediaFromRequest('image')
                    ->usingName($newFileName)
                    ->usingFileName($newFileName)
                    ->withCustomProperties(['root' => $prefix . '-' . $slider->id])
                    ->toMediaCollection($prefix . '-media', 'media');
            }

            flash(trans('Pearls::messages.success.updated', ['item' => ucfirst($this->title_singular)]))->success();
        } catch (\Exception $exception) {
            logger($exception->getMessage());
        }

        return redirectTo($this->resource_url);
    }    
    
    public function destroy(SliderRequest $request, Slider $slider)
    {
        try {
            $slider->delete();

            $message = ['level' => 'success', 'message' => trans('Pearls::messages.success.deleted', ['item' => ucfirst($this->breadcrumb)])];
        } catch (\Exception $exception) {
            log_exception($exception, User::class, 'destroy');
            $message = ['level' => 'error', 'message' => $exception->getMessage()];
            $code = 400;
        }

        return response()->json($message, $code ?? 200);
    }
}