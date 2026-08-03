<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCompanyRequest;
use App\Http\Requests\UpdateCompanyRequest;
use App\Models\Company;
use App\Services\CloudinaryService;
use App\Traits\HasCompanyAccess;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    use HasCompanyAccess;

    public function __construct(private CloudinaryService $cloudinary) {}

    public function index(Request $request): JsonResponse
    {
        $user = $request->user();
        abort_if(!$user->can('view_company'), 403, 'No autorizado.');

        $companies = $this->getAccessibleCompanies($user)
            ->withCount(['cards', 'services', 'products'])
            ->latest()
            ->get();

        return response()->json($companies);
    }

    public function store(StoreCompanyRequest $request): JsonResponse
    {
        $user = $request->user();
        abort_if(!$user->can('create_company'), 403, 'No autorizado.');

        $data = $request->validated();
        $data['user_id'] = $user->id;

        if ($request->hasFile('logo')) {
            $folder = CloudinaryService::companyFolder($data['slug'], 'logo');
            $uploaded = $this->cloudinary->upload($request->file('logo'), $folder);
            $data['logo_path']      = $uploaded['url'];
            $data['logo_public_id'] = $uploaded['public_id'];
        }

        if ($request->hasFile('icon')) {
            $folder = CloudinaryService::companyFolder($data['slug'], 'icon');
            $uploaded = $this->cloudinary->upload($request->file('icon'), $folder);
            $data['icon_path']      = $uploaded['url'];
            $data['icon_public_id'] = $uploaded['public_id'];
        }

        $company = Company::create($data);

        return response()->json($company, 201);
    }

    public function show(Request $request, Company $company): JsonResponse
    {
        $user = $request->user();
        abort_if(!$user->can('view_company'), 403, 'No autorizado.');
        $this->enforceCompanyAccess($user, $company);

        $company->load(['cards', 'services', 'products']);

        $plan = $company->currentPlan();
        $limits = null;
        if ($plan) {
            $limits = [
                'cards'    => ['current' => $company->cards->count(), 'limit' => $plan->max_cards],
                'products' => ['current' => $company->products->count(), 'limit' => $plan->max_products],
                'services' => ['current' => $company->services->count(), 'limit' => $plan->max_services],
            ];
        }

        return response()->json([
            'company' => $company,
            'limits'  => $limits,
        ]);
    }

    public function update(UpdateCompanyRequest $request, Company $company): JsonResponse
    {
        $user = $request->user();
        abort_if(!$user->can('edit_company'), 403, 'No autorizado.');
        $this->enforceCompanyAccess($user, $company);

        $data = $request->validated();

        if ($request->hasFile('logo')) {
            if ($company->logo_public_id) {
                $this->cloudinary->destroy($company->logo_public_id);
            }
            $slug   = $data['slug'] ?? $company->slug;
            $folder = CloudinaryService::companyFolder($slug, 'logo');
            $uploaded = $this->cloudinary->upload($request->file('logo'), $folder);
            $data['logo_path']      = $uploaded['url'];
            $data['logo_public_id'] = $uploaded['public_id'];
        }

        if ($request->hasFile('icon')) {
            if ($company->icon_public_id) {
                $this->cloudinary->destroy($company->icon_public_id);
            }
            $slug   = $data['slug'] ?? $company->slug;
            $folder = CloudinaryService::companyFolder($slug, 'icon');
            $uploaded = $this->cloudinary->upload($request->file('icon'), $folder);
            $data['icon_path']      = $uploaded['url'];
            $data['icon_public_id'] = $uploaded['public_id'];
        }

        $company->update($data);

        return response()->json($company->fresh());
    }

    public function destroy(Request $request, Company $company): JsonResponse
    {
        $user = $request->user();
        abort_if(!$user->can('delete_company'), 403, 'No autorizado.');
        $this->enforceCompanyAccess($user, $company);

        if ($company->logo_public_id) {
            $this->cloudinary->destroy($company->logo_public_id);
        }

        if ($company->icon_public_id) {
            $this->cloudinary->destroy($company->icon_public_id);
        }

        $company->delete();

        return response()->json(null, 204);
    }
}
