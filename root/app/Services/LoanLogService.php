<?php

namespace App\Services;

use App\Repositories\LoanLogRepository;
use App\Models\LoanLog;

class LoanLogService
{
    protected $loanLogRepo;

    public function __construct(LoanLogRepository $loanLogRepo)
    {
        $this->loanLogRepo = $loanLogRepo;
    }

    public function borrowAsset($userId, $assetId)
    {
        return $this->loanLogRepo->create([
            'user_id' => $userId,
            'asset_id' => $assetId,
            'loaned_at' => now(),
        ]);
    }

    public function returnAsset($assetId)
    {
        $log = $this->loanLogRepo->findActiveByAsset($assetId);
        if ($log) {
            $this->loanLogRepo->makeReturned($log);
        }
    }

    public function getLoanLogHistory($perPage = 15)
    {
        return $this->loanLogRepo->getAllPaginated($perPage);
    }

}