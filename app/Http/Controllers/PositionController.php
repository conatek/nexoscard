<?php

namespace App\Http\Controllers;

use App\Http\Requests\PositionCreateRequest;
use App\Http\Requests\PositionEditRequest;
use App\Models\Area;
use App\Models\Company;
use App\Models\Position;
use App\Models\PositionCriticalityLevel;
use App\Models\PositionRiskClass;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class PositionController extends Controller
{
    public function getPositions() {
        $results = [];
        $user_company_id = auth()->user()->company_id;
        $positions = Position::where('company_id', $user_company_id)->get();
        $criticality_levels = PositionCriticalityLevel::all();
        $risk_classes = PositionRiskClass::all();
        $positions_data = [];

        foreach ($positions as $position) {
            $data = [];

            $data['id'] = $position->id;
            $data['company_id'] = $position->company_id;
            $data['area_id'] = $position->area_id;
            $data['area'] = Area::where('id', $position->area_id)->first();
            $data['criticality_level_id'] = $position->criticality_level_id;
            $data['criticality_level'] = PositionCriticalityLevel::where('id', $position->criticality_level_id)->first();
            $data['risk_class_id'] = $position->risk_class_id;
            $data['risk_class'] = PositionRiskClass::where('id', $position->risk_class_id)->first();
            $data['name'] = $position->name;
            $data['estimated_salary'] = $position->estimated_salary;
            $data['description'] = $position->description;

            array_push($positions_data, $data);
        }
        
        
        $results['positions'] = $positions_data;
        $results['criticality_levels'] = $criticality_levels;
        $results['risk_classes'] = $risk_classes;

        return $results;
    }

    public function index()
    {
        abort_if(Gate::denies('position_index'), 403);

        $user = auth()->user();
        $company = Company::where('id', $user->company_id)->first();

        $positions = Position::all();
        return view('back.positions.index', compact('company', 'positions'));
    }

    public function create()
    {
        abort_if(Gate::denies('position_create'), 403);
        $areas = Area::all()->sortBy("name");
        return view('back.positions.create', compact('areas'));
    }

    public function store(PositionCreateRequest $request)
    {
        // Las validaciones se realizan en PositionCreateRequest

        $area = Area::where('id', $request->area_id)->first();
        $criticality_level = PositionCriticalityLevel::where('id', $request->criticality_level_id)->first();
        $risk_class = PositionRiskClass::where('id', $request->risk_class_id)->first();

        $data = array(
            'company_id' => $request->company_id,
            'area_id' => $request->area_id,
            'criticality_level_id' => $request->criticality_level_id,
            'risk_class_id' => $request->risk_class_id,
            'name' => $request->name,
            'estimated_salary' => $request->estimated_salary,
            'description' => $request->description,
        );

        $position = Position::create($data);

        $data['id'] = $position->id;
        $data['area'] = $area;
        $data['criticality_level'] = $criticality_level;
        $data['risk_class'] = $risk_class;

        return response()->json([
            'message'=>'Cargo creado exitosamente!',
            'position'=>$data
        ]);
    }

    public function show(Position $position)
    {
        abort_if(Gate::denies('position_show'), 403);
        return view('back.positions.show', compact('position'));
    }

    public function edit(Position $position)
    {
        abort_if(Gate::denies('position_edit'), 403);
        $areas = Area::all()->sortBy("name");
        return view('back.positions.edit', compact('position', 'areas'));
    }

    public function update(PositionEditRequest $request, Position $position)
    {
        // Las validaciones se realizan en PositionEditRequest

        $area = Area::where('id', $request->area_id)->first();
        $criticality_level = PositionCriticalityLevel::where('id', $request->criticality_level_id)->first();
        $risk_class = PositionRiskClass::where('id', $request->risk_class_id)->first();

        $data = array(
            'id' => $request->id,
            'company_id' => $request->company_id,
            'area_id' => $request->area_id,
            'criticality_level_id' => $request->criticality_level_id,
            'risk_class_id' => $request->risk_class_id,
            'name' => $request->name,
            'estimated_salary' => $request->estimated_salary,
            'description' => $request->description,
        );

        $position = Position::where('id', $data['id'])->first();
        $position->update($data);

        $data['area'] = $area;
        $data['criticality_level'] = $criticality_level;
        $data['risk_class'] = $risk_class;

        return response()->json([
            'message'=>'Cargo actualizado exitosamente!',
            'position'=>$data
        ]);
    }

    public function destroy($position_data_id)
    {
        try {
            $position = Position::where('id', $position_data_id)->first();

            $position->delete();
            
            return response()->json([
                'message'=>'Cargo eliminada exitosamente!',
                'position'=>$position,
            ]);

        } catch(Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }
}
