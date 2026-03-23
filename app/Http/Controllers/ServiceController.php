<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreServiceRequest;
use App\Models\Company;
use App\Models\Service;
use App\Services\CloudinaryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function __construct(private CloudinaryService $cloudinary) {}

    public function index(Company $company): JsonResponse
    {
        return response()->json($company->services);
    }

    public function show(Company $company, Service $service): JsonResponse
    {
        abort_if($service->company_id !== $company->id, 404);

        return response()->json($service);
    }

    public function store(StoreServiceRequest $request, Company $company): JsonResponse
    {
        $this->authorizeOwner($request, $company);

        $data = $request->validated();
        $data['company_id'] = $company->id;

        if ($request->hasFile('image')) {
            $uploaded = $this->cloudinary->upload($request->file('image'), 'companies/services');
            $data['image_path'] = $uploaded['url'];
        }

        $service = Service::create($data);

        return response()->json($service, 201);
    }

    public function update(StoreServiceRequest $request, Company $company, Service $service): JsonResponse
    {
        $this->authorizeOwner($request, $company);
        abort_if($service->company_id !== $company->id, 404);

        $data = $request->validated();

        if ($request->hasFile('image')) {
            if ($service->image_path) {
                $this->cloudinary->destroy($service->image_path);
            }
            $uploaded = $this->cloudinary->upload($request->file('image'), 'companies/services');
            $data['image_path'] = $uploaded['url'];
        }

        $service->update($data);

        return response()->json($service->fresh());
    }

    public function destroy(Request $request, Company $company, Service $service): JsonResponse
    {
        $this->authorizeOwner($request, $company);
        abort_if($service->company_id !== $company->id, 404);

        if ($service->image_path) {
            $this->cloudinary->destroy($service->image_path);
        }

        $service->delete();

        return response()->json(null, 204);
    }

    private function authorizeOwner(Request $request, Company $company): void
    {
        abort_if($company->user_id !== $request->user()->id, 403, 'No autorizado.');
    }
}
