<?php

namespace App\Http\Controllers;

use App\Http\Requests\CollaboratorMedicalExaminationCreateRequest;
use App\Http\Requests\CollaboratorMedicalExaminationEditRequest;
use App\Models\CollaboratorMedicalExamination;
use App\Models\MedicalExaminationType;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Exception;
use Illuminate\Http\Request;

class CollaboratorMedicalExaminationController extends Controller
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
            $medical_examinations = CollaboratorMedicalExamination::where('collaborator_id', $collaborator_id)->orderBy('examination_date', 'desc')->get();
            $medical_examination_data = [];

            foreach ($medical_examinations as $medical_examination) {
                $data = [];

                $data['id'] = $medical_examination->id;
                $data['examination_type'] = MedicalExaminationType::where('id', $medical_examination->examination_type_id)->first()->name;
                $data['examination_type_id'] = $medical_examination->examination_type_id;
                $data['observations'] = $medical_examination->observations;
                $data['examination_date'] = $medical_examination->examination_date;
                $data['result_public_id'] = $medical_examination->result_public_id;
                $data['result_url'] = $medical_examination->result_url;
                $data['status'] = '';

                array_push($medical_examination_data, $data);
            }

            return response()->json([
                'medical_examination_data' => $medical_examination_data,
            ]);
        } catch(Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }

    public function store(CollaboratorMedicalExaminationCreateRequest $request)
    {
        // Las validaciones se realizan en CollaboratorMedicalExaminationCreateRequest

        try{
            $company_id = auth()->user()->company_id;

            if($request->hasFile('examination_result')) {
                $file = request()->file('examination_result');

                $cloudinary_object = Cloudinary::upload($file->getRealPath(), ['folder' =>  'mh/' . env("APP_ENV", "local") . '/' . $company_id . '/collaborators/medical_examinations']);
                $result_public_id = $cloudinary_object->getPublicId();
                $result_url = $cloudinary_object->getSecurePath();

                $data = array(
                    'collaborator_id' => $request->collaborator_id,
                    'examination_type_id' => $request->examination_type_id,
                    'examination_date' => $request->examination_date,
                    'observations' => $request->examination_observations,
                    'result_public_id' => $result_public_id,
                    'result_url' => $result_url
                );
            } else {
                $data = array(
                    'collaborator_id' => $request->collaborator_id,
                    'examination_type_id' => $request->examination_type_id,
                    'examination_date' => $request->examination_date,
                    'observations' => $request->examination_observations
                );
            }

            $medical_examination_data = CollaboratorMedicalExamination::create($data);

            $examination_type = MedicalExaminationType::where('id', $medical_examination_data->examination_type_id)->first();

            $data['id'] = $medical_examination_data->id;
            $data['examination_type'] = $examination_type->name;

            return response()->json([
                'message'=>'Examen médico ingresado exitosamente!',
                'medical_examination_data'=>$data,
            ]);
        } catch(Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }

    public function update(CollaboratorMedicalExaminationEditRequest $request, $medical_examination_id)
    {
        // Las validaciones se realizan en CollaboratorMedicalExaminationEditRequest

        try {
            $company_id = auth()->user()->company_id;

            $data = array(
                'id' => $medical_examination_id,
                'collaborator_id' => $request->collaborator_id,
                'examination_type_id' => $request->examination_type_id,
                'examination_date' => $request->examination_date,
                'observations' => $request->examination_observations,
            );

            $url = isset($medical_examination['result_url']) ? $medical_examination['result_url'] : null;
            $public_id = isset($medical_examination['result_public_id']) ? $medical_examination['result_public_id'] : null;
            if($request->hasFile('examination_result')) {
                if($public_id != null) {
                    Cloudinary::destroy($public_id);
                }
                $file = request()->file('examination_result');
                $cloudinary_object = Cloudinary::upload($file->getRealPath(), ['folder' =>  'mh/' . env("APP_ENV", "local") . '/' . $company_id . '/collaborators/medical_examinations']);
                $result_public_id = $cloudinary_object->getPublicId();
                $result_url = $cloudinary_object->getSecurePath();

                $data['result_public_id'] = $result_public_id;
                $data['result_url'] = $result_url;
            } else {
                $data['result_public_id'] = $public_id;
                $data['result_url'] = $url;
            }


            $medical_examination = CollaboratorMedicalExamination::where('id', $medical_examination_id)->first();
            $medical_examination->update($data);

            $examination_type = MedicalExaminationType::where('id', $request->examination_type_id)->first();

            $data['examination_type'] = $examination_type->name;

            return response()->json([
                'message'=>'Evaluación médica actualizada exitosamente!',
                'medical_examination'=>$data
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }

    public function downloadResult($medical_examination_id) {
        try {
            $medical_examination = CollaboratorMedicalExamination::where('id', $medical_examination_id)->first();
            $result_download_url = $medical_examination->result_url;

            return response()->json([
                'result_download_url' => $result_download_url
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }

    public function destroy($medical_examination_id)
    {
        // abort_if(Gate::denies('collaborator_destroy'), 403);

        try {
            $medical_examination = CollaboratorMedicalExamination::where('id', $medical_examination_id)->first();
            if(isset($medical_examination['result_public_id'])) {
                $public_id = $medical_examination['result_public_id'];
                Cloudinary::destroy($public_id);
            }

            $medical_examination->delete();

            return response()->json([
                'message'=>'Evaluación médica eliminada exitosamente!',
                'medical_examination'=>$medical_examination
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }
}
