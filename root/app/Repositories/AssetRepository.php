<?php

namespace App\Repositories;

use App\Models\Asset;

class AssetRepository
{
    public function query()
    {
        return Asset::query();
    }

    public function paginateWithFilters(array $filters, int $perPage = 10)
    {
        $query = $this->query();

        if (!empty($filters['asset_number'])) {
            $query->where('asset_number', 'like' , '%' . $filters['asset_number'] . '%');
        }

        if (!empty($filters['keyword'])) {
            $query->where('name', 'like', '%' . $filters['keyword'] . '%');
        }

        if (!empty($filters['category_id'])) {
            $query->where('category_id', $filters['category_id']);
        }

        if (!empty($filters['user_id'])) {
            $query->where('user_id', $filters['user_id']);
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        return $query->with(['category', 'user'])->paginate($perPage);
    }

    public function create(array $data): Asset
    {
        return Asset::create($data);
    }

    public function update(Asset $asset, array $data): bool
    {
        return $asset->update($data);
    }

    public function delete(Asset $asset): bool
    {
        return $asset->delete();
    }
}
