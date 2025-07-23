<?php

namespace App\Http\Controllers;

use App\Services\AssetService;
use App\Models\Asset;
use Illuminate\Http\Request;

class AssetController extends Controller
{
    protected $assetService;

    public function __construct(AssetService $assetService)
    {
        $this->assetService = $assetService;
    }

    public function index(Request $request)
    {
        $filters = $request->only(['keyword', 'category_id', 'user_id', 'status', 'asset_number']);
        $assets = $this->assetService->getAssets($filters);
        $categories = $this->assetService->getCategories();
        $users = $this->assetService->getUsers();

        return view('assets.index', compact('assets', 'categories', 'users'));
    }

    public function show(Asset $asset)
    {
        return view('assets.show', compact('asset'));
    }

    public function create()
    {
        $categories = $this->assetService->getCategories();
        $users = $this->assetService->getUsers();
        $departments = $this->assetService->getDepartments();
        //資産番号作成
        if (isset($data['category_id'])) {
            $data['asset_number'] = $this->generateAssetNumber($data['category_id']);
        }
        return view('assets.create', compact('categories', 'users', 'departments'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'asset_number' => 'nullable|string|max:100|unique:assets',
            'acquisition_date' => 'required|date',
            'price' => 'required|integer',
            'location' => 'nullable|exists:departments,id',
            'status' => 'required|string',
            'category_id' => 'nullable|exists:categories,id',
            'user_id' => 'nullable|exists:users,id',
        ]);

        // asset_number はカテゴリから自動生成
        $validated['asset_number'] = $this->assetService->generateAssetNumber($validated['category_id']);

        $this->assetService->createAsset($validated);

        return redirect()->route('assets.index')->with('success', '資産を登録しました');
    }

    public function edit(Asset $asset)
    {
        $users = $this->assetService->getUsers();
        $departments = $this->assetService->getDepartments();
        $categories = $this->assetService->getCategories();
        return view('assets.edit', compact('asset', 'users', 'departments', 'categories'));
    }

    public function update(Request $request, Asset $asset)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'asset_number' => 'required|string|max:100|unique:assets,asset_number,' . $asset->id,
            'acquisition_date' => 'required|date',
            'price' => 'required|integer',
            'location' => 'nullable|exists:departments,id',
            'status' => 'required|string',
            'category_id' => 'nullable|exists:categories,id',
            'user_id' => 'nullable|exists:users,id',
        ]);

        $this->assetService->updateAsset($asset, $validated);

        return redirect()->route('assets.index')->with('success', '資産を更新しました。');
    }

    public function destroy(Asset $asset)
    {
        $this->assetService->deleteAsset($asset);

        return redirect()->route('assets.index')->with('success', '資産を削除しました。');
    }
}
