<?php

namespace Pearls\Modules\CMS\Http\Controllers;

use Pearls\Base\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Pearls\Modules\CMS\DataTables\ChroniclesDataTable;
use Pearls\Modules\CMS\Http\Requests\ChronicleRequest;
use Pearls\Modules\CMS\Models\Chronicle;

class ChronicleController extends BaseController
{
    
    protected $breadcrumb = 'chronicles';

    public function __construct()
    {
        $this->resource_url = config('cms.models.chronicle.resource_url');
        $this->title = 'Chronicles';
        $this->title_singular = 'Chronicle';
        parent::__construct();
    }

    public function index(ChronicleRequest $request, ChroniclesDataTable $dataTable)
    {
        $this->setViewSharedData(['breadcrumb' => $this->breadcrumb]);
        
        return $dataTable->render('CMS::chronicles.index');
    }

    public function create(ChronicleRequest $request)
    {
        $chronicle = new Chronicle();

        $this->setViewSharedData([
            'title_singular' => 'Create ' . $this->title_singular,
            'breadcrumb' => \Str::singular($this->breadcrumb) . '_create_edit'
        ]);

        return view('CMS::chronicles.create_edit')->with(compact('chronicle'));
    }

    public function store(ChronicleRequest $request)
    {
        try {
            $chronicle_data = $request->all();

            $youtube_link = \Arr::get($chronicle_data, 'youtube_link');

            if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $youtube_link, $matches)) {
                $chronicle_data['youtube_link'] = $matches[1];
            } else {
                $chronicle_data['youtube_link'] = '';
            }

            Chronicle::create($chronicle_data);

            flash(trans('Pearls::messages.success.created', ['item' => ucfirst($this->title_singular)]))->success();
        } catch (\Exception $exception) {
            log_exception($exception, Chronicle::class, 'store');
        }

        return redirectTo($this->resource_url);
    }

   public function edit(ChronicleRequest $request, Chronicle $chronicle)
    {
        $this->setViewSharedData([
            'title_singular' => "Update [{$chronicle->title}]",
            'breadcrumb' => \Str::singular($this->breadcrumb) . '_create_edit'
        ]);

        return view('CMS::chronicles.create_edit')->with(compact('chronicle'));
    }

    /**
     * @param UserRequest $request
     * @param User $user
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(ChronicleRequest $request, Chronicle $chronicle)
    {

        try {
            $chronicle_data = $request->all();
            $youtube_link = \Arr::get($chronicle_data, 'youtube_link');

            if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $youtube_link, $matches)) {
                $chronicle_data['youtube_link'] = $matches[1];
            } else {
                $chronicle_data['youtube_link'] = '';
            }

            $chronicle->update($chronicle_data);

            flash(trans('Pearls::messages.success.updated', ['item' => ucfirst($this->title_singular)]))->success();
        } catch (\Exception $exception) {
            logger($exception->getMessage());
        }

        return redirectTo($this->resource_url);
    }

    /**
     * @param UserRequest $request
     * @param User $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(ChronicleRequest $request, Chronicle $chronicle)
    {
        try {
            $chronicle->delete();

            $message = ['level' => 'success', 'message' => trans('Pearls::messages.success.deleted', ['item' => ucfirst($this->breadcrumb)])];
        } catch (\Exception $exception) {
            log_exception($exception, Chronicle::class, 'destroy');
            $message = ['level' => 'error', 'message' => $exception->getMessage()];
            $code = 400;
        }

        return response()->json($message, $code ?? 200);
    }
}