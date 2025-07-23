<?php

namespace App\Services;

use App\Repositories\AssetRepository;
use Illuminate\Support\Facades\Auth;
use App\Models\Category;
use App\Models\User;
use App\Models\Department;
use App\Models\Asset;

class AssetService
{
    protected $assetRepository;

    public function __construct(AssetRepository $assetRepository)
    {
        $this->assetRepository = $assetRepository;
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
            $data['asset_number'] = $this->generateAssetNumber($data['category_id']);
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

    //資産番号生成
    public function generateAssetNumber(int $categoryId): string
    {
        // カテゴリIDを2桁で
        $prefix = $prefix = ((int)$categoryId) * 10;

        // そのカテゴリの最新の資産番号を取得
        $latestAsset = Asset::where('category_id', $categoryId)
            ->where('asset_number', 'like', $prefix . '%') // prefixから始まる資産番号だけ検索
            ->orderByDesc('asset_number')
            ->first();

        if ($latestAsset) {
            // 最新の資産番号の連番部分を取り出す
            $latestNumber = $latestAsset->asset_number;
            $lastDigit = (int)substr($latestNumber, strlen((string)$prefix));

            // 連番を1増やす
            $newDigit = $lastDigit + 1;
        } else {
            // 最初は連番1からスタート
            $newDigit = 1;
        }

        // 連番部分を必要に応じてゼロ埋め
        $newDigitStr = str_pad((string)$newDigit, 4, '0', STR_PAD_LEFT);

        return $prefix . $newDigitStr;
    }

}
