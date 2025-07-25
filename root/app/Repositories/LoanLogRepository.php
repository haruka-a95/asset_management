<?php
namespace App\Repositories;

use App\Models\LoanLog;

class LoanLogRepository
{
    public function create(array $data)
    {
        return LoanLog::create($data);
    }

    public function findActiveByAsset($assetId)
    {
        return LoanLog::where('asset_id', $assetId)
                        ->whereNull('returned_at')
                        ->first();
    }

    public function makeReturned(LoanLog $log)
    {
        $log->update(['returned_at' => now()]);
    }

    public function getAllPaginated($perPage = 15)
    {
        return LoanLog::with(['user', 'asset'])->orderByDesc('loaned_at')->paginate($perPage);
    }
}