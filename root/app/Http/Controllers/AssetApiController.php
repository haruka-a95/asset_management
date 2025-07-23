<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use App\Services\AssetService;

class AssetApiController extends Controller
{
    protected AssetService $assetService;

    public function __construct(AssetService $assetService)
    {
        $this->assetService = $assetService;
    }

    public function getNextAssetNumber(int $categoryId): JsonResponse
    {
        $assetNumber = $this->assetService->generateAssetNumber($categoryId);
        return response()->json(['asset_number' => $assetNumber]);
    }
}
