<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Category;
use App\Models\Department;
use App\Enums\AssetStatus;

class AssetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Asset::query();

        if ($request->filled('keyword')) {
            $query->where('name', 'like', '%' . $request->keyword . '%');
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $assets = $query->with(['category', 'user'])->paginate(10)->appends($request->all());

        $categories = Category::all();
        $users = User::all();

        $count = Asset::count();//件数

        return view('assets.index', compact('assets', 'users', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::all();
        $users = User::all();
        $departments = Department::all();
        return view('assets.create', compact('users', 'categories', 'departments'));
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
            'location' => 'nullable|exists:departments,id',
            'status' => 'required|string',
            'category_id' => 'nullable|exists:categories,id',
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
        $users = User::all();
        $departments = Department::all();
        return view('assets.edit', compact('asset', 'users', 'departments'));
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
        'asset_number' => 'required|string|max:100|unique:assets,asset_number,' . $asset->id,
        'acquisition_date' => 'required|date',
        'price' => 'required|integer',
        'location' => 'nullable|exists:departments,id',
        'status' => 'required|string',
        'category_id' => 'nullable|exists:categories,id',
        'user_id' => 'nullable|exists:users,id',
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
