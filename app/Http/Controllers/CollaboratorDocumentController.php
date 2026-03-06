<?php

namespace App\Http\Controllers;

use App\Http\Requests\CollaboratorDocumentCreateRequest;
use App\Http\Requests\CollaboratorDocumentEditRequest;
use App\Models\CollaboratorDocument;
use App\Models\ContractualDocumentType;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Exception;
use Illuminate\Http\Request;

class CollaboratorDocumentController extends Controller
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
            $documents = CollaboratorDocument::where('collaborator_id', $collaborator_id)->orderBy('id', 'asc')->get();
            $document_data = [];

            foreach ($documents as $document) {
                $data = [];

                $data['id'] = $document->id;
                $data['document_type'] = ContractualDocumentType::where('id', $document->document_type_id)->first()->name;
                $data['document_type_id'] = $document->document_type_id;
                $data['observations'] = $document->observations;
                $data['document_public_id'] = $document->document_public_id;
                $data['document_url'] = $document->document_url;
                $data['status'] = '';

                array_push($document_data, $data);
            }

            return response()->json([
                'document_data' => $document_data,
            ]);
        } catch(Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }

    public function store(CollaboratorDocumentCreateRequest $request)
    {
        // Las validaciones se realizan en CollaboratorDocumentCreateRequest

        try{
            $company_id = auth()->user()->company_id;

            if($request->hasFile('document')) {
                $file = request()->file('document');

                $cloudinary_object = Cloudinary::upload($file->getRealPath(), ['folder' =>  'mh/' . env("APP_ENV", "local") . '/' . $company_id . '/collaborators/documents']);
                $document_public_id = $cloudinary_object->getPublicId();
                $document_url = $cloudinary_object->getSecurePath();

                $data = array(
                    'collaborator_id' => $request->collaborator_id,
                    'document_type_id' => $request->document_type_id,
                    'document_public_id' => $document_public_id,
                    'document_url' => $document_url,
                    'observations' => $request->document_observations
                );
            } else {
                $data = array(
                    'collaborator_id' => $request->collaborator_id,
                    'document_type_id' => $request->document_type_id,
                    'observations' => $request->document_observations
                );
            }

            $document_data = CollaboratorDocument::create($data);

            $data['id'] = $document_data->id;
            $data['document_type'] = ContractualDocumentType::where('id', $document_data->document_type_id)->first()->name;

            return response()->json([
                'message'=>'Documento ingresado exitosamente!',
                'document_data'=>$data,
            ]);
        } catch(Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }

    public function update(CollaboratorDocumentEditRequest $request, $document_id)
    {
        // Las validaciones se realizan en CollaboratorDocumentEditRequest

        try {
            $document = CollaboratorDocument::where('id', $document_id)->first();
            $company_id = auth()->user()->company_id;

            $data = array(
                'id' => $document_id,
                'collaborator_id' => $request->collaborator_id,
                'document_type_id' => $request->document_type_id,
                'observations' => $request->document_observations,
            );

            $url = isset($document['document_url']) ? $document['document_url'] : null;
            $public_id = isset($document['document_public_id']) ? $document['document_public_id'] : null;
            if($request->hasFile('document')) {
                if($public_id != null) {
                    Cloudinary::destroy($public_id);
                }
                $file = request()->file('document');
                $cloudinary_object = Cloudinary::upload($file->getRealPath(), ['folder' =>  'mh/' . env("APP_ENV", "local") . '/' . $company_id . '/collaborators/documents']);
                $document_public_id = $cloudinary_object->getPublicId();
                $document_url = $cloudinary_object->getSecurePath();

                $data['document_public_id'] = $document_public_id;
                $data['document_url'] = $document_url;
            } else {
                $data['document_public_id'] = $public_id;
                $data['document_url'] = $url;
            }

            $document->update($data);

            $data['document_type'] = ContractualDocumentType::where('id', $data['document_type_id'])->first()->name;

            return response()->json([
                'message'=>'Documento actualizado exitosamente!',
                'document_data'=>$data
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }

    public function downloadDocument($document_id) {
        try {
            $document = CollaboratorDocument::where('id', $document_id)->first();
            $document_download_url = $document->document_url;

            return response()->json([
                'document_download_url' => $document_download_url
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }

    public function destroy($document_id)
    {
        // abort_if(Gate::denies('collaborator_destroy'), 403);

        try {
            $document = CollaboratorDocument::where('id', $document_id)->first();
            if(isset($document['document_public_id'])) {
                $public_id = $document['document_public_id'];
                Cloudinary::destroy($public_id);
            }

            $document->delete();

            return response()->json([
                'message'=>'Documento eliminado exitosamente!',
                'document'=>$document
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }
}
