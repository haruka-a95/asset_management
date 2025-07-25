<?php
namespace App\Services;

use App\Models\Asset;

class AssetNumberService
{
    //資産番号生成
    public function generate(int $categoryId): string
    {
        // カテゴリIDを2桁で
        $prefix = $prefix = ((int)$categoryId) * 10;

        // そのカテゴリの最新の資産番号を取得
        $latestAsset = Asset::where('category_id', $categoryId)
            ->where('asset_number', 'like', $prefix . '%') // prefixから始まる資産番号だけ検索
            ->orderByDesc('asset_number')
            ->first();

        $newDigit = $latestAsset
            ? ((int)substr($latestAsset->asset_number, strlen((string)$prefix))) + 1
            : 1;

        return $prefix . str_pad((string)$newDigit, 4, '0', STR_PAD_LEFT);
    }

}