<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCompanyRequest;
use App\Http\Requests\UpdateCompanyRequest;
use App\Models\Company;
use App\Services\CloudinaryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function __construct(private CloudinaryService $cloudinary) {}

    public function index(Request $request): JsonResponse
    {
        $companies = Company::where('user_id', $request->user()->id)
            ->withCount(['cards', 'services', 'products'])
            ->latest()
            ->get();

        return response()->json($companies);
    }

    public function store(StoreCompanyRequest $request): JsonResponse
    {
        $data = $request->validated();
        $data['user_id'] = $request->user()->id;

        if ($request->hasFile('logo')) {
            $uploaded = $this->cloudinary->upload($request->file('logo'), 'companies/logos');
            $data['logo_path'] = $uploaded['url'];
        }

        $company = Company::create($data);

        return response()->json($company, 201);
    }

    public function show(Company $company): JsonResponse
    {
        $company->load(['cards', 'services', 'products']);

        return response()->json($company);
    }

    public function update(UpdateCompanyRequest $request, Company $company): JsonResponse
    {
        $this->authorizeOwner($request, $company);

        $data = $request->validated();

        if ($request->hasFile('logo')) {
            // Eliminar imagen anterior si existe
            if ($company->logo_path) {
                // Extraer public_id si se guardó (aquí asumimos que logo_path es la URL)
                $this->cloudinary->destroy($company->logo_path);
            }
            $uploaded = $this->cloudinary->upload($request->file('logo'), 'companies/logos');
            $data['logo_path'] = $uploaded['url'];
        }

        // Merge de design_settings: conserva valores previos, pisa solo los enviados
        if (isset($data['design_settings'])) {
            $data['design_settings'] = array_merge(
                $company->design_settings,
                $data['design_settings']
            );
        }

        $company->update($data);

        return response()->json($company->fresh());
    }

    public function destroy(Request $request, Company $company): JsonResponse
    {
        $this->authorizeOwner($request, $company);

        if ($company->logo_path) {
            $this->cloudinary->destroy($company->logo_path);
        }

        $company->delete();

        return response()->json(null, 204);
    }

    // Devuelve los CSS variables para el frontend
    public function theme(Company $company): JsonResponse
    {
        $s = $company->design_settings;

        return response()->json([
            '--color-primary'    => $s['primary_color'],
            '--color-secondary'  => $s['secondary_color'],
            '--color-background' => $s['background_color'],
            '--color-text'       => $s['text_color'],
            '--color-accent'     => $s['accent_color'],
            '--font-family'      => $s['font_family'],
            '--border-radius'    => $s['border_radius'] . 'px',
        ]);
    }

    private function authorizeOwner(Request $request, Company $company): void
    {
        abort_if($company->user_id !== $request->user()->id, 403, 'No autorizado.');
    }
}
