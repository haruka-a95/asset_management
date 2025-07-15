<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AssetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $assets = Asset::get();//全件取得
        $count = Asset::count();//件数
        return view('assets.index', compact('assets'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('assets.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'asset_number' => 'required|string|max:100|unique:assets',
            'acquisition_date' => 'required|date',
            'price' => 'required|integer',
            'location' => 'nullable|string|max:255',
            'status' => 'required|string',
        ]);

        $validated['user_id'] = Auth::id();

        Asset::create($validated);

        return redirect()->route('assets.index')->with('success', '資産を登録しました');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Asset  $asset
     * @return \Illuminate\Http\Response
     */
    public function show(Asset $asset)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Asset  $asset
     * @return \Illuminate\Http\Response
     */
    public function edit(Asset $asset)
    {
        return view('assets.edit', compact('asset'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Asset  $asset
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Asset $asset)
    {
        // バリデーション
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'category' => 'nullable|string|max:255',
        'value' => 'nullable|numeric',
        // 他のフィールドも必要に応じて追加
    ]);

    // 更新
    $asset->update($validated);

    // リダイレクト（例: 一覧画面へ）
    return redirect()->route('assets.index')->with('success', '資産を更新しました。');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Asset  $asset
     * @return \Illuminate\Http\Response
     */
    public function destroy(Asset $asset)
    {
        $asset->delete();

        return redirect()->route('assets.index')->with('success', '資産を削除しました。');
    }
}
