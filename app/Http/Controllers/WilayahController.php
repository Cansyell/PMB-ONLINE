<?php
// app/Http/Controllers/WilayahController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravolt\Indonesia\Models\City;
use Laravolt\Indonesia\Models\District;
use Laravolt\Indonesia\Models\Village;

class WilayahController extends Controller
{
    public function cities($provinceCode)
    {
        $cities = City::where('province_code', $provinceCode)
                    ->orderBy('name')
                    ->get(['code', 'name']);
        return response()->json($cities);
    }

    public function districts($cityCode)
    {
        $districts = District::where('city_code', $cityCode)
                    ->orderBy('name')
                    ->get(['code', 'name']);
        return response()->json($districts);
    }

    public function villages($districtCode)
    {
        $villages = Village::where('district_code', $districtCode)
                    ->orderBy('name')
                    ->get(['code', 'name']);
        return response()->json($villages);
    }
}