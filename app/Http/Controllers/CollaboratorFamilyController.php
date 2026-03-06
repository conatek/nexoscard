<?php

namespace App\Http\Controllers;

use App\Http\Requests\CollaboratorFamilyCreateRequest;
use App\Http\Requests\CollaboratorFamilyEditRequest;
use App\Models\CollaboratorFamily;
use App\Models\Occupation;
use App\Models\Relationship;
use App\Models\SexType;
use DateTime;
use Illuminate\Http\Request;
use Exception;

class CollaboratorFamilyController extends Controller
{
    public function __construct()
    {
        // Aplicamos el middleware AJAX solo a métodos específicos
        $this->middleware(function ($request, $next) {
            if (!request()->ajax()) {
                abort(403, 'Acceso denegado');
            }
            return $next($request);
        });
    }

    public function show($collaborator_id) {
        try{
            $relatives = CollaboratorFamily::where('collaborator_id', $collaborator_id)->orderBy('created_at', 'desc')->get();
            $relatives_data = [];

            // dd($relatives);

            foreach ($relatives as $relative) {
                $data = [];

                $data['id'] = $relative->id;
                $data['name'] = $relative->name;
                $data['first_surname'] = $relative->first_surname;
                $data['second_surname'] = $relative->second_surname;
                $data['relationship'] = Relationship::where('id', $relative->relationship_id)->first();
                $data['relationship_id'] = $relative->relationship_id;
                $data['occupation'] = Occupation::where('id', $relative->occupation_id)->first();
                $data['occupation_id'] = $relative->occupation_id;
                $data['sex'] = SexType::where('id', $relative->sex_type_id)->first();
                $data['sex_id'] = $relative->sex_type_id;
                $data['birth_date'] = $relative->birth_date;
                $data['status'] = '';

                array_push($relatives_data, $data);
            }

            return response()->json([
                'relatives_data' => $relatives_data,
            ]);
        } catch(Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }

    public function store(CollaboratorFamilyCreateRequest $request)
    {
        // Las validaciones se realizan en CollaboratorFamilyCreateRequest

        try{
            $relationship = Relationship::where('id', $request->relationship_id)->first();
            $occupation = Occupation::where('id', $request->occupation_id)->first();
            $sex = SexType::where('id', $request->sex_type_id)->first();

            $data = array(
                'collaborator_id' => $request->collaborator_id,
                'name' => $request->name,
                'first_surname' => $request->first_surname,
                'second_surname' => $request->second_surname,
                'relationship_id' => $request->relationship_id,
                'occupation_id' => $request->occupation_id,
                'sex_type_id' => $request->sex_type_id,
                'birth_date' => $request->birth_date,
            );

            $relative_data = CollaboratorFamily::create($data);

            $data['id'] = $relative_data->id;
            $data['relationship'] = $relationship;
            $data['occupation'] = $occupation;
            $data['sex'] = $sex;

            return response()->json([
                'message'=>'Familiar creado exitosamente!',
                'relative_data'=>$data,
            ]);
        } catch(Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }

    public function update(CollaboratorFamilyEditRequest $request)
    {
        // Las validaciones se realizan en CollaboratorFamilyEditRequest

        try{
            $relationship = Relationship::where('id', $request->relationship_id)->first();
            $occupation = Occupation::where('id', $request->occupation_id)->first();
            $sex = SexType::where('id', $request->sex_type_id)->first();

            $data = array(
                'id' => $request->id,
                'collaborator_id' => $request->collaborator_id,
                'name' => $request->name,
                'first_surname' => $request->first_surname,
                'second_surname' => $request->second_surname,
                'relationship_id' => $request->relationship_id,
                'sex_type_id' => $request->sex_type_id,
                'occupation_id' => $request->occupation_id,
                'birth_date' => $request->birth_date,
            );

            $relative_data = CollaboratorFamily::where('id', $data['id'])->first();
            $relative_data->update($data);

            $data['relationship'] = $relationship;
            $data['occupation'] = $occupation;
            $data['sex'] = $sex;

            return response()->json([
                'message'=>'Familiar actualizado exitosamente!',
                'relative_data'=>$data,
            ]);
        } catch(Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }

    public function destroy(CollaboratorFamily $relative_data)
    {
        try {
            $relative = CollaboratorFamily::where('collaborator_id', $relative_data->collaborator_id)->first();

            $relative->delete();

            return response()->json([
                'message'=>'Información de familiar eliminada exitosamente!',
                'relative'=>$relative,
            ]);

        } catch(Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }

    public function getAge($birth_date)
    {
        $birth = new DateTime($birth_date);
        $today = new DateTime(date("Y-m-d"));
        $difference = $today->diff($birth);
        return $difference->format("%y");
    }



}
