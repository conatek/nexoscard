<?php

namespace App\Http\Controllers;

use App\Models\City;
use Illuminate\Http\Request;

class ProvinceController extends Controller
{
    public function getCities(Request $request)
    {
        $result = [];

        $cities = City::where('province_id', $request->province)
            ->orderBy('name')
            ->get();

        $result['status'] = 'success';
        $result['cities'] = $cities;

        return $result;
    }
}
