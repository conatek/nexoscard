<?php

namespace App\Http\Controllers;

use App\Http\Requests\CompanyCreateRequest;
use App\Http\Requests\CompanyEditRequest;
use App\Models\City;
use App\Models\Company;
use App\Models\CompanyType;
use App\Models\DocumentType;
use App\Models\Industry;
use App\Models\Province;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class CompaniesController extends Controller
{
    public function __construct()
    {
        // Aplicamos el middleware AJAX solo a métodos específicos
        $this->middleware(function ($request, $next) {
            if (!request()->ajax()) {
                abort(403, 'Acceso denegado');
            }
            return $next($request);
        })->except(['index']);
    }

    public function index()
    {
        abort_if(Gate::denies('company_index'), 403, 'ACCESO DENEGADO');

        $companies = Company::orderBy('created_at', 'desc')
            ->orderBy('updated_at', 'desc')
            ->get();

        return view('back.companies.index', compact('companies'));
    }

    public function create()
    {
        abort_if(Gate::denies('company_create'), 403);

        $result = [];

        $company_types = CompanyType::all();
        $document_types = DocumentType::all();
        $provinces = Province::all();
        $industry_types = Industry::all();

        $result['company_types'] = $company_types;
        $result['document_types'] = $document_types;
        $result['provinces'] = $provinces;
        $result['industry_types'] = $industry_types;

        return $result;
    }

    public function store(CompanyCreateRequest $request)
    {
        try {
            // Asignación masiva de los datos a través del método fill()
            $company = new Company();
            $company->fill($request->only([
                'company_type_id',
                'company_name',
                'identification_type_id',
                'identification_number',
                'province_id',
                'city_id',
                'address',
                'industry_type_id',
                'size',
                'founded_at',
                'description',
                'contact_name',
                'contact_first_surname',
                'contact_second_surname',
                'website',
                'email',
                'phone',
                'cellphone',
                'facebook',
                'instagram',
                'linkedin',
                'x_twitter',
                'youtube'
            ]));

            // Manejo del campo 'is_active'
            $company->is_active = $request->filled('is_active') ? 1 : 0;

            // Guardar la empresa
            $company->save();

            // Subida y actualización del logo, si existe
            if ($request->hasFile('logo')) {
                $this->uploadLogo($company, $request->file('logo'));
            }

            return response()->json([
                'message' => 'Empresa creada exitosamente!',
                'company' => $company,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }

    protected function uploadLogo(Company $company, $logoFile)
    {
        $cloudinary_object = Cloudinary::upload($logoFile->getRealPath(), [
            'folder' => 'mh/' . env("APP_ENV", "local") . '/' . $company->id . '/logo'
        ]);

        $company->update([
            'logo_public_id' => $cloudinary_object->getPublicId(),
            'logo_url' => $cloudinary_object->getSecurePath(),
        ]);
    }

    public function show(Company $company)
    {
        abort_if(Gate::denies('company_show'), 403);

        $result = [];

        $company_type = CompanyType::where('id', $company->company_type_id)->first();
        $industry_type = Industry::where('id', $company->industry_type_id)->first();
        $identification_type = DocumentType::where('id', $company->identification_type_id)->first();
        $province = Province::where('id', $company->province_id)->first();
        $city = City::where('id', $company->city_id)->first();

        $result['company'] = $company;
        $result['company_type'] = $company_type;
        $result['industry_type'] = $industry_type;
        $result['identification_type'] = $identification_type;
        $result['province'] = $province;
        $result['city'] = $city;

        return $result;
    }

    public function edit(Company $company)
    {
        abort_if(Gate::denies('company_edit'), 403);

        $result = [];

        $company_types = CompanyType::all();
        $document_types = DocumentType::all();
        $provinces = Province::all();
        $industry_types = Industry::all();

        $result['company'] = $company;
        $result['company_types'] = $company_types;
        $result['document_types'] = $document_types;
        $result['provinces'] = $provinces;
        $result['industry_types'] = $industry_types;

        return $result;
    }

    public function update(CompanyEditRequest $request, Company $company)
    {
        // Las validaciones se realizan en CompanyEditRequest

        try {
            $data = array(
                'company_name' => $request->company_name,
                'company_type_id' => $request->company_type_id,
                'identification_type_id' => $request->identification_type_id,
                'identification_number' => $request->identification_number,
                'province_id' => $request->province_id,
                'city_id' => $request->city_id,
                'address' => $request->address,
                'industry_type_id' => $request->industry_type_id,
                'size' => $request->size,
                'founded_at' => $request->founded_at,
                'description' => $request->description,

                'contact_name' => $request->contact_name,
                'contact_first_surname' => $request->contact_first_surname,
                'contact_second_surname' => $request->contact_second_surname,
                'website' => $request->website,
                'email' => $request->email,
                'phone' => $request->phone,
                'cellphone' => $request->cellphone,

                'facebook' => $request->facebook,
                'instagram' => $request->instagram,
                'linkedin' => $request->linkedin,
                'x_twitter' => $request->x_twitter,
                'youtube' => $request->youtube
            );

            // Manejo del campo 'is_active'
            $company->is_active = $request->filled('is_active') ? 1 : 0;

            // Guardar la empresa
            $company->update($data);

            // Subida y actualización del logo, si existe
            if ($request->hasFile('logo')) {
                $this->uploadLogo($company, $request->file('logo'));
            }

            return response()->json([
                'message' => 'Empresa actualizada exitosamente!',
                'company' => $company,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        abort_if(Gate::denies('company_destroy'), 403);

        $company = Company::find($id);

        try {
            if(isset($company['logo_public_id'])) {
                $public_id = $company['logo_public_id'];
                Cloudinary::destroy($public_id);
            }

            $company->delete();

            return response()->json([
                'message'=>'Empresa eliminada exitosamente!',
                'company'=>$company
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }
}
