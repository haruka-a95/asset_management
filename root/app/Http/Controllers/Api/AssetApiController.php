<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use App\Services\AssetNumberService;

class AssetApiController extends Controller
{
    protected $assetNumberService;

    public function __construct(AssetNumberService $assetNumberService)
    {
        $this->assetNumberService = $assetNumberService;
    }

    public function getNextAssetNumber(int $categoryId): JsonResponse
    {
        $assetNumber = $this->assetNumberService->generate($categoryId);
        return response()->json(['asset_number' => $assetNumber]);
    }
}
