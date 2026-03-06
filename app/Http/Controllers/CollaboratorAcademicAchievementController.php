<?php

namespace App\Http\Controllers;

use App\Http\Requests\CollaboratorAcademicCreateRequest;
use App\Http\Requests\CollaboratorAcademicEditRequest;
use App\Models\AcademicAchievementType;
use App\Models\CollaboratorAcademicAchievement;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Exception;
use Illuminate\Http\Request;

class CollaboratorAcademicAchievementController extends Controller
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
            $academic_achievements = CollaboratorAcademicAchievement::where('collaborator_id', $collaborator_id)->orderBy('achievement_type_id', 'desc')->orderBy('end_date', 'desc')->get();
            $academic_achievements_data = [];

            foreach ($academic_achievements as $academic_achievement) {
                $data = [];

                $data['id'] = $academic_achievement->id;
                $data['achievement_type'] = AcademicAchievementType::where('id', $academic_achievement->achievement_type_id)->first()->type;
                $data['achievement_type_id'] = $academic_achievement->achievement_type_id;
                $data['degree'] = $academic_achievement->degree;
                $data['institution'] = $academic_achievement->institution;
                $data['end_date'] = $academic_achievement->end_date;
                $data['certificate_public_id'] = $academic_achievement->certificate_public_id;
                $data['certificate_url'] = $academic_achievement->certificate_url;
                $data['status'] = '';

                array_push($academic_achievements_data, $data);
            }

            return response()->json([
                'academic_achievements_data' => $academic_achievements_data,
            ]);
        } catch(Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }

    public function store(CollaboratorAcademicCreateRequest $request)
    {
        // Las validaciones se realizan en CollaboratorAcademicCreateRequest

        try{
            $company_id = auth()->user()->company_id;

            $achievement_type = AcademicAchievementType::where('id', $request->achievement_type_id)->first();

            if($request->hasFile('certificate')) {
                $file = request()->file('certificate');

                $cloudinary_object = Cloudinary::upload($file->getRealPath(), ['folder' =>  'mh/' . env("APP_ENV", "local") . '/' . $company_id . '/collaborators/academic']);
                $certificate_public_id = $cloudinary_object->getPublicId();
                $certificate_url = $cloudinary_object->getSecurePath();

                $data = array(
                    'collaborator_id' => $request->collaborator_id,
                    'achievement_type_id' => $request->achievement_type_id,
                    'degree' => $request->degree,
                    'institution' => $request->institution,
                    'end_date' => $request->end_date,
                    'certificate_public_id' => $certificate_public_id,
                    'certificate_url' => $certificate_url,
                );
            } else {
                $data = array(
                    'collaborator_id' => $request->collaborator_id,
                    'achievement_type_id' => $request->achievement_type_id,
                    'degree' => $request->degree,
                    'institution' => $request->institution,
                    'end_date' => $request->end_date,
                );
            }

            $academic_data = CollaboratorAcademicAchievement::create($data);

            $data['id'] = $academic_data->id;
            $data['achievement_type'] = $achievement_type->type;

            return response()->json([
                'message'=>'Información académica creada exitosamente!',
                'academic_data'=>$data,
            ]);
        } catch(Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }

    public function update(CollaboratorAcademicEditRequest $request, $academic_achievement_id)
    {
        // Las validaciones se realizan en CollaboratorAcademicEditRequest

        try {
            $company_id = auth()->user()->company_id;

            $achievement_type = AcademicAchievementType::where('id', $request->achievement_type_id)->first();

            $data = array(
                'id' => $academic_achievement_id,
                'collaborator_id' => $request->collaborator_id,
                'achievement_type_id' => $request->achievement_type_id,
                'degree' => $request->degree,
                'institution' => $request->institution,
                'end_date' => $request->end_date,
            );

            $url = isset($academic_achievement['certificate_url']) ? $academic_achievement['certificate_url'] : null;
            $public_id = isset($academic_achievement['certificate_public_id']) ? $academic_achievement['certificate_public_id'] : null;
            if($request->hasFile('certificate')) {
                if($public_id != null) {
                    Cloudinary::destroy($public_id);
                }
                $file = request()->file('certificate');
                $cloudinary_object = Cloudinary::upload($file->getRealPath(), ['folder' =>  'mh/' . env("APP_ENV", "local") . '/' . $company_id . '/collaborators/academic']);
                $certificate_public_id = $cloudinary_object->getPublicId();
                $certificate_url = $cloudinary_object->getSecurePath();

                $data['certificate_public_id'] = $certificate_public_id;
                $data['certificate_url'] = $certificate_url;
            } else {
                $data['certificate_public_id'] = $public_id;
                $data['certificate_url'] = $url;
            }

            $academic_achievement = CollaboratorAcademicAchievement::where('id', $academic_achievement_id)->first();
            $academic_achievement->update($data);

            $data['achievement_type'] = $achievement_type->type;

            return response()->json([
                'message' => 'Información académica actualizada exitosamente!',
                'academic_data' => $data
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }

    public function downloadCertificate($academic_achievement_id) {
        try {
            $academic_achievement = CollaboratorAcademicAchievement::where('id', $academic_achievement_id)->first();
            $certificate_download_url = $academic_achievement->certificate_url;

            return response()->json([
                'certificate_download_url' => $certificate_download_url
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }

    public function destroy($academic_achievement_id)
    {
        // abort_if(Gate::denies('collaborator_destroy'), 403);

        try {
            $academic_achievement = CollaboratorAcademicAchievement::where('id', $academic_achievement_id)->first();
            if(isset($academic_achievement['certificate_public_id'])) {
                $public_id = $academic_achievement['certificate_public_id'];
                Cloudinary::destroy($public_id);
            }

            $academic_achievement->delete();

            return response()->json([
                'message'=>'Información académica eliminada exitosamente!',
                'academic_achievement'=>$academic_achievement
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }
}
