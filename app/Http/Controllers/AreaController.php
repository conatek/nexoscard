<?php

namespace App\Http\Controllers;

use App\Http\Requests\AreaCreateRequest;
use App\Http\Requests\AreaEditRequest;
use App\Models\Area;
use App\Models\Campus;
use App\Models\Collaborator;
use App\Models\Company;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class AreaController extends Controller
{
    public function getAreas() {
        $results = [];
        $user_company_id = auth()->user()->company_id;
        $areas = Area::where('company_id', $user_company_id)->get();
        $areas_data = [];

        foreach ($areas as $area) {
            $data = [];

            $data['id'] = $area->id;
            $data['company_id'] = $area->company_id;
            $data['campus_id'] = $area->campus_id;
            $data['campus'] = Campus::where('id', $area->campus_id)->first();
            $data['leader_id'] = $area->leader_id;
            $data['leader'] = Collaborator::where('id', $area->leader_id)->first();
            $data['name'] = $area->name;
            $data['description'] = $area->description;

            array_push($areas_data, $data);
        }
        
        
        $results['areas'] = $areas_data;

        return $results;
    }

    public function index()
    {
        abort_if(Gate::denies('area_index'), 403);

        $user = auth()->user();
        $company = Company::where('id', $user->company_id)->first();

        $areas = Area::all();
        return view('back.areas.index', compact('company', 'areas'));
    }

    public function create()
    {
        abort_if(Gate::denies('area_create'), 403);
        return view('back.areas.create');
    }

    public function store(AreaCreateRequest $request)
    {
        // Las validaciones se realizan en AreaCreateRequest

        $campus = Campus::where('id', $request->campus_id)->first();
        $leader = Collaborator::where('id', $request->leader_id)->first();

        $data = array(
            'company_id' => $request->company_id,
            'campus_id' => $request->campus_id,
            'leader_id' => $request->leader_id,
            'name' => $request->name,
            'description' => $request->description,
        );

        $area = Area::create($data);

        $data['id'] = $area->id;
        $data['campus'] = $campus;
        $data['leader'] = $leader;

        return response()->json([
            'message'=>'Área creada exitosamente!',
            'area'=>$data
        ]);
    }

    public function show(Area $area)
    {
        abort_if(Gate::denies('area_show'), 403);
        return view('back.areas.show', compact('area'));
    }

    public function edit(Area $area)
    {
        abort_if(Gate::denies('area_edit'), 403);
        return view('back.areas.edit', compact('area'));
    }

    public function update(AreaEditRequest $request, Area $area)
    {
        // Las validaciones se realizan en AreaEditRequest

        $campus = Campus::where('id', $request->campus_id)->first();
        $leader = Collaborator::where('id', $request->leader_id)->first();

        $data = array(
            'id' => $request->id,
            'company_id' => $request->company_id,
            'campus_id' => $request->campus_id,
            'leader_id' => $request->leader_id,
            'name' => $request->name,
            'description' => $request->description,
        );

        $area = Area::where('id', $data['id'])->first();
        $area->update($data);

        $data['campus'] = $campus;
        $data['leader'] = $leader;

        return response()->json([
            'message'=>'Área actualizada exitosamente!',
            'area'=>$data
        ]);
    }

    public function destroy($area_data_id)
    {
        try {
            $area = Area::where('id', $area_data_id)->first();

            $area->delete();
            
            return response()->json([
                'message'=>'Área eliminada exitosamente!',
                'area'=>$area,
            ]);

        } catch(Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }
}
