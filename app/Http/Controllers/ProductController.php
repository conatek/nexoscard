<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Models\Company;
use App\Models\Product;
use App\Services\CloudinaryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct(private CloudinaryService $cloudinary) {}

    public function index(Company $company): JsonResponse
    {
        return response()->json($company->products);
    }

    public function store(StoreProductRequest $request, Company $company): JsonResponse
    {
        $this->authorizeOwner($request, $company);

        $data = $request->validated();
        $data['company_id'] = $company->id;

        if ($request->hasFile('image')) {
            $uploaded = $this->cloudinary->upload($request->file('image'), 'companies/products');
            $data['image_path'] = $uploaded['url'];
        }

        $product = Product::create($data);

        return response()->json($product, 201);
    }

    public function update(StoreProductRequest $request, Company $company, Product $product): JsonResponse
    {
        $this->authorizeOwner($request, $company);
        abort_if($product->company_id !== $company->id, 404);

        $data = $request->validated();

        if ($request->hasFile('image')) {
            if ($product->image_path) {
                $this->cloudinary->destroy($product->image_path);
            }
            $uploaded = $this->cloudinary->upload($request->file('image'), 'companies/products');
            $data['image_path'] = $uploaded['url'];
        }

        $product->update($data);

        return response()->json($product->fresh());
    }

    public function destroy(Request $request, Company $company, Product $product): JsonResponse
    {
        $this->authorizeOwner($request, $company);
        abort_if($product->company_id !== $company->id, 404);

        if ($product->image_path) {
            $this->cloudinary->destroy($product->image_path);
        }

        $product->delete();

        return response()->json(null, 204);
    }

    private function authorizeOwner(Request $request, Company $company): void
    {
        abort_if($company->user_id !== $request->user()->id, 403, 'No autorizado.');
    }
}
