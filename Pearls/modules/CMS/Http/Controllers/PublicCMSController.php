<?php

namespace Pearls\Modules\CMS\Http\Controllers;

use Pearls\Base\Http\Controllers\PublicBaseController;
use Illuminate\Http\Request;
use Pearls\Modules\CMS\Models\Chronicle;
use Pearls\Modules\City\Models\City;

class PublicCMSController extends PublicBaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $pageTitle = 'Home';
        
        $chronicle = Chronicle::where(['status' => 'active'])->orderBy('id', 'desc')->first();

        $citiesWithActiveChronicles = City::whereHas('chronicles', function ($query) {
            $query->where('status', 'active');
        })->withCount(['chronicles' => function ($query) {
            $query->where('status', 'active');
        }])->get(['lat', 'lng', 'name', 'id', 'chronicles_count']);
        
        $citiesMarkersData = $citiesWithActiveChronicles->map(function ($city) {
            $s = $city->chronicles_count > 1 ? 's' : '';
            return [
                'latLng' => [$city->lat, $city->lng],
                'name' => 'Chronicle'.$s. ' (' . $city->chronicles_count . ')',
                'id' => $city->id,
            ];
        })->toArray();
        
        $citiesMarkersData = json_encode($citiesMarkersData);

        return view('frontend.pages.index', compact('pageTitle', 'chronicle', 'citiesMarkersData'));
    }

    public function chronicles(Request $request)
    {
        $pageTitle = 'Chronicles';
        $search = "";
        $chronicles = Chronicle::where('status', 'active');
        
        if(!empty($search = $request->search)) {
            $chronicles = $chronicles->where('title', 'like', '%'.$search.'%')->orWhere('description', 'like', '%'.$search.'%');
        }
        
        $chronicles = $chronicles->orderBy('id', 'desc')->paginate(9);

        return view('frontend.pages.chronicles', compact('pageTitle', 'chronicles', 'search'));
    }

    public function showChronicle($id)
    {
        $chronicle = Chronicle::where(['id' => $id, 'status' => 'active'])->first();
        if(empty($chronicle)) {
            abort(404);
        }
        $pageTitle = $chronicle->title;
        return view('frontend.pages.single_chronicle', compact('pageTitle', 'chronicle'));
    }

    public function aboutUs()
    {
        $pageTitle = 'About Us';
        return view('frontend.pages.about_us', compact('pageTitle'));
    }

    public function supportOurWork()
    {
        $pageTitle = 'Support Our Work';
        return view('frontend.pages.support_our_work', compact('pageTitle'));
    }

    public function getMarkers($cityId, $chronicleId = null)
    {
        $city = City::where('id', $cityId)->first();

        $chronicles = Chronicle::where('status', 'active')->where('city_id', $cityId);

        if(!empty($chronicleId)) {
            $chronicles = $chronicles->where('id', '!=', $chronicleId);
        }

        $chronicles = $chronicles->orderBy('id', 'desc')->get();

        $locationsMarkersData = $chronicles->map(function ($chronicle) {
            return [
                'latLng' => [$chronicle->lat, $chronicle->lng],
                'name' => $chronicle->title,
                'locId' => $chronicle->id,
            ];
        });

        $cityId = $city->id;

        $city = view('frontend.partials.city_description', compact('city'))->render();

        $data = [
            'cityId' => $cityId,
            'city' => $city,
            'locationsMarkersData' => $locationsMarkersData->toJson(),
        ];

        return response()->json($data);
    }

    public function getChronicleByMarker($chronicleId) {
        $chronicle = Chronicle::where(['status' => 'active', 'id' => $chronicleId])->first();
        return view('frontend.partials.chronicle_box', compact('chronicle'))->render();
    }
}