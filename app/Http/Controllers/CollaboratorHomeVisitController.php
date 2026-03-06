<?php

namespace App\Http\Controllers;

use App\Http\Requests\CollaboratorHomeVisitCreateRequest;
use App\Http\Requests\CollaboratorHomeVisitEditRequest;
use App\Models\CollaboratorHomeVisit;
use App\Models\HomeVisitType;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Exception;
use Illuminate\Http\Request;

class CollaboratorHomeVisitController extends Controller
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
            $home_visits = CollaboratorHomeVisit::where('collaborator_id', $collaborator_id)->orderBy('visit_date', 'desc')->get();
            $home_visit_data = [];

            foreach ($home_visits as $home_visit) {
                $data = [];

                $data['id'] = $home_visit->id;
                $data['home_visit_type'] = HomeVisitType::where('id', $home_visit->home_visit_type_id)->first()->name;
                $data['home_visit_type_id'] = $home_visit->home_visit_type_id;
                // $data['home_visit_observations'] = $home_visit->observations;
                $data['observations'] = $home_visit->observations;
                $data['visit_date'] = $home_visit->visit_date;
                $data['next_visit_date'] = $home_visit->next_visit_date;
                $data['report_public_id'] = $home_visit->report_public_id;
                $data['report_url'] = $home_visit->report_url;
                $data['status'] = '';

                array_push($home_visit_data, $data);
            }

            return response()->json([
                'home_visit_data' => $home_visit_data,
            ]);
        } catch(Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }

    public function store(CollaboratorHomeVisitCreateRequest $request)
    {
        // Las validaciones se realizan en CollaboratorHomeVisitCreateRequest

        try{
            $company_id = auth()->user()->company_id;

            if($request->hasFile('home_visit_report')) {
                $file = request()->file('home_visit_report');

                $cloudinary_object = Cloudinary::upload($file->getRealPath(), ['folder' =>  'mh/' . env("APP_ENV", "local") . '/' . $company_id . '/collaborators/home_visits']);
                $report_public_id = $cloudinary_object->getPublicId();
                $report_url = $cloudinary_object->getSecurePath();

                $data = array(
                    'collaborator_id' => $request->collaborator_id,
                    'home_visit_type_id' => $request->home_visit_type_id,
                    'visit_date' => $request->visit_date,
                    'next_visit_date' => $request->next_visit_date,
                    'observations' => $request->home_visit_observations,
                    'report_public_id' => $report_public_id,
                    'report_url' => $report_url
                );
            } else {
                $data = array(
                    'collaborator_id' => $request->collaborator_id,
                    'home_visit_type_id' => $request->home_visit_type_id,
                    'visit_date' => $request->visit_date,
                    'next_visit_date' => $request->next_visit_date,
                    'observations' => $request->home_visit_observations,
                );
            }

            $home_visit_data = CollaboratorHomeVisit::create($data);

            $data['id'] = $home_visit_data->id;
            $data['home_visit_type'] = HomeVisitType::where('id', $home_visit_data->home_visit_type_id)->first()->name;

            return response()->json([
                'message'=>'Visita domiciliaria ingresada exitosamente!',
                'home_visit_data'=>$data,
            ]);
        } catch(Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }

    public function update(CollaboratorHomeVisitEditRequest $request, $home_visit_id)
    {
        // Las validaciones se realizan en CollaboratorHomeVisitEditRequest

        try {
            $home_visit = CollaboratorHomeVisit::where('id', $home_visit_id)->first();
            $company_id = auth()->user()->company_id;

            $data = array(
                'id' => $home_visit_id,
                'collaborator_id' => $request->collaborator_id,
                'home_visit_type_id' => $request->home_visit_type_id,
                'visit_date' => $request->visit_date,
                'next_visit_date' => $request->next_visit_date,
                'observations' => $request->home_visit_observations,
            );

            $url = isset($home_visit['report_url']) ? $home_visit['report_url'] : null;
            $public_id = isset($home_visit['report_public_id']) ? $home_visit['report_public_id'] : null;
            if($request->hasFile('home_visit_report')) {
                if($public_id != null) {
                    Cloudinary::destroy($public_id);
                }
                $file = request()->file('home_visit_report');
                $cloudinary_object = Cloudinary::upload($file->getRealPath(), ['folder' =>  'mh/' . env("APP_ENV", "local") . '/' . $company_id . '/collaborators/home_visits']);
                $report_public_id = $cloudinary_object->getPublicId();
                $report_url = $cloudinary_object->getSecurePath();

                $data['report_public_id'] = $report_public_id;
                $data['report_url'] = $report_url;
            } else {
                $data['report_public_id'] = $public_id;
                $data['report_url'] = $url;
            }

            $home_visit->update($data);

            $data['home_visit_type'] = HomeVisitType::where('id', $home_visit->home_visit_type_id)->first()->name;

            return response()->json([
                'message'=>'Visita domiciliaria actualizada exitosamente!',
                'home_visit'=>$data
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }

    public function downloadReport($home_visit_id) {
        try {
            $home_visit = CollaboratorHomeVisit::where('id', $home_visit_id)->first();
            $report_download_url = $home_visit->report_url;

            return response()->json([
                'report_download_url' => $report_download_url
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }

    public function destroy($home_visit_id)
    {
        // abort_if(Gate::denies('collaborator_destroy'), 403);

        try {
            $home_visit = CollaboratorHomeVisit::where('id', $home_visit_id)->first();
            if(isset($home_visit['report_public_id'])) {
                $public_id = $home_visit['report_public_id'];
                Cloudinary::destroy($public_id);
            }

            $home_visit->delete();

            return response()->json([
                'message'=>'Visita domiciliaria eliminada exitosamente!',
                'home_visit'=>$home_visit
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }
}
