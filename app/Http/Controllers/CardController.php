<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCardRequest;
use App\Http\Requests\UpdateCardRequest;
use App\Models\Card;
use App\Models\Company;
use App\Services\CloudinaryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CardController extends Controller
{
    public function __construct(private CloudinaryService $cloudinary) {}

    public function index(Company $company): JsonResponse
    {
        $cards = $company->cards()->latest()->get();

        return response()->json($cards);
    }

    public function store(StoreCardRequest $request, Company $company): JsonResponse
    {
        $this->authorizeOwner($request, $company);

        $data = $request->validated();
        $data['company_id'] = $company->id;

        if ($request->hasFile('photo')) {
            $uploaded = $this->cloudinary->upload($request->file('photo'), 'companies/cards');
            $data['photo_path'] = $uploaded['url'];
        }

        $card = Card::create($data);

        return response()->json($card, 201);
    }

    public function show(Company $company, Card $card): JsonResponse
    {
        abort_if($card->company_id !== $company->id, 404);

        return response()->json($card);
    }

    public function update(UpdateCardRequest $request, Company $company, Card $card): JsonResponse
    {
        $this->authorizeOwner($request, $company);
        abort_if($card->company_id !== $company->id, 404);

        $data = $request->validated();

        if ($request->hasFile('photo')) {
            if ($card->photo_path) {
                $this->cloudinary->destroy($card->photo_path);
            }
            $uploaded = $this->cloudinary->upload($request->file('photo'), 'companies/cards');
            $data['photo_path'] = $uploaded['url'];
        }

        $card->update($data);

        return response()->json($card->fresh());
    }

    public function destroy(Request $request, Company $company, Card $card): JsonResponse
    {
        $this->authorizeOwner($request, $company);
        abort_if($card->company_id !== $company->id, 404);

        if ($card->photo_path) {
            $this->cloudinary->destroy($card->photo_path);
        }

        $card->delete();

        return response()->json(null, 204);
    }

    private function authorizeOwner(Request $request, Company $company): void
    {
        abort_if($company->user_id !== $request->user()->id, 403, 'No autorizado.');
    }
}
