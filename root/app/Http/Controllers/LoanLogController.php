<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\LoanLogService;
use App\Models\Asset;
use App\Enums\AssetStatus;
use App\Http\Requests\StoreLoanLogRequest;

class LoanLogController extends Controller
{
    protected $loanLogService;

    public function __construct(LoanLogService $loanLogService)
    {
        $this->loanLogService = $loanLogService;
    }

    public function index()
    {
        $logs = $this->loanLogService->getLoanLogHistory();
        return view('loan_logs.index', compact('logs'));
    }

    public function borrow(StoreLoanLogRequest $request)
    {
        $request->validated();

        //対象を取得
        $asset = Asset::findOrFail($request->asset_id);
        //ステータスを変更
        $asset->status = AssetStatus::IN_USE->value;
        $asset->save();

        $userId = auth()->id();

        $this->loanLogService->borrowAsset($userId, $request->asset_id);

        return back()->with('success', '貸出を記録しました。');
    }

    public function return(StoreLoanLogRequest $request)
    {
        $request->validated();

        //対象を取得
        $asset = Asset::findOrFail($request->asset_id);

         if ($asset->user_id !== Auth::id()) {
            abort(403, 'あなたはこの資産の使用者ではありません');
        }
        //ステータスを変更
        $asset->status = AssetStatus::STORED->value;
        //使用者をリセット
        $asset->user_id = null;
        $asset->save();

        $this->loanLogService->returnAsset($request->asset_id);

        return back()->with('success', '返却を記録しました。');
    }
}
