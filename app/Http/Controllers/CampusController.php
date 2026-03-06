<?php

namespace App\Http\Controllers;

use App\Http\Requests\CampusCreateRequest;
use App\Http\Requests\CampusEditRequest;
use App\Models\Campus;
use App\Models\City;
use App\Models\Company;
use App\Models\Province;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class CampusController extends Controller
{
    public function getCampuses() {
        $results = [];
        $user_company_id = auth()->user()->company_id;
        $campuses = Campus::where('company_id', $user_company_id)->get();
        $campuses_data = [];

        foreach ($campuses as $campus) {
            $data = [];

            $data['id'] = $campus->id;
            $data['company_id'] = $campus->company_id;
            $data['province_id'] = $campus->province_id;
            $data['province'] = Province::where('id', $campus->province_id)->first();
            $data['city_id'] = $campus->city_id;
            $data['city'] = City::where('id', $campus->city_id)->first();
            $data['name'] = $campus->name;
            $data['address'] = $campus->address;
            $data['phone'] = $campus->phone;
            $data['email'] = $campus->email;

            array_push($campuses_data, $data);
        }
        
        
        $results['campuses'] = $campuses_data;

        return $results;
    }

    public function index()
    {
        $user = auth()->user();
        $company = Company::where('id', $user->company_id)->first();

        abort_if(Gate::denies('campus_index'), 403);
        $campuses = Campus::all()->sortBy('name');
        return view('back.campuses.index', compact('company', 'campuses'));
    }

    public function create()
    {
        $user = auth()->user();
        $company = Company::where('id', $user->company_id)->first();

        abort_if(Gate::denies('campus_create'), 403);
        $provinces = Province::all()->sortBy("name");
        return view('back.campuses.create', compact('company', 'provinces'));
    }

    public function store(CampusCreateRequest $request)
    {
        $province = Province::where('id', $request->province_id)->first();
        $city = City::where('id', $request->city_id)->first();

        $data = array(
            'company_id' => $request->company_id,
            'province_id' => $request->province_id,
            'city_id' => $request->city_id,
            'name' => $request->name,
            'address' => $request->address,
            'phone' => $request->phone,
            'email' => $request->email,
        );

        $campus = Campus::create($data);

        $data['id'] = $campus->id;
        $data['province'] = $province;
        $data['city'] = $city;

        return response()->json([
            'message'=>'Sede creada exitosamente!',
            'campus'=>$data
        ]);
    }

    public function show(Campus $campus)
    {
        abort_if(Gate::denies('campus_show'), 403);
        return view('back.campuses.show', compact('campus'));
    }

    public function edit(Request $campus)
    {
        abort_if(Gate::denies('campus_edit'), 403);
        $provinces = Province::all()->sortBy("name");
        return view('back.campuses.edit', compact('campus', 'provinces'));
    }

    public function update(CampusEditRequest $request)
    {
        // Las validaciones se realizan en PositionEditRequest

        $province = Province::where('id', $request->province_id)->first();
        $city = City::where('id', $request->city_id)->first();

        $data = array(
            'id' => $request->id,
            'company_id' => $request->company_id,
            'province_id' => $request->province_id,
            'city_id' => $request->city_id,
            'name' => $request->name,
            'address' => $request->address,
            'phone' => $request->phone,
            'email' => $request->email,
        );

        $campus = Campus::where('id', $data['id'])->first();
        $campus->update($data);

        $data['province'] = $province;
        $data['city'] = $city;

        return response()->json([
            'message'=>'Sede actualizada exitosamente!',
            'campus'=>$data
        ]);
    }

    // public function destroy(Campus $campus)
    // {
    //     abort_if(Gate::denies('campus_destroy'), 403);
    //     $campus->delete();
    //     return back()->with('success', 'Sede eliminada correctamente.');
    // }

    public function destroy($campus_data_id)
    {
        try {
            $campus = Campus::where('id', $campus_data_id)->first();

            $campus->delete();
            
            return response()->json([
                'message'=>'Sede eliminada exitosamente!',
                'campus'=>$campus,
            ]);

        } catch(Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }
}
