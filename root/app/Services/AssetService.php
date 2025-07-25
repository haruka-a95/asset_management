<?php

namespace App\Services;

use App\Repositories\AssetRepository;
use Illuminate\Support\Facades\Auth;
use App\Models\Category;
use App\Models\User;
use App\Models\Department;
use App\Models\Asset;
use App\Services\AssetNumberService;

class AssetService
{
    protected $assetRepository;
    protected $assetNumberService;

    public function __construct(AssetRepository $assetRepository, AssetNumberService $assetNumberService)
    {
        $this->assetRepository = $assetRepository;
        $this->assetNumberService = $assetNumberService;
    }

    public function getAssets(array $filters = [])
    {
        return $this->assetRepository->paginateWithFilters($filters);
    }

    public function getCategories()
    {
        return Category::all();
    }

    public function getUsers()
    {
        return User::all();
    }

    public function getDepartments()
    {
        return Department::all();
    }

    public function createAsset(array $data)
    {
        //資産番号をセット
        if (empty($data['asset_number']) && !empty($data['category_id'])) {
            $data['asset_number'] = $this->assetNumberService->generate($data['category_id']);
        }

        $data['user_id'] = Auth::id();
        return $this->assetRepository->create($data);
    }

    public function updateAsset(Asset $asset, array $data)
    {
        return $this->assetRepository->update($asset, $data);
    }

    public function deleteAsset(Asset $asset)
    {
        return $this->assetRepository->delete($asset);
    }
}
