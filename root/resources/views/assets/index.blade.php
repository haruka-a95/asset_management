<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            資産一覧
        </h2>
    </x-slot>

    <x-flash-message />
    <div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <a href="{{ route('assets.create') }}" class="mb-4 inline-block bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">＋ 新規登録</a>

    <!-- 検索機能 -->
     <div class="container bg-white m-3 p-5">
        <p>検索</p>
        <form method="GET" action="{{ route('assets.index') }}" id="searchForm" class="mb-6">
            <!-- 資産番号検索 -->
            <input type="number" name="asset_number" value="{{ request('asset_number') }}" placeholder="資産番号で検索" class="border rounded px-4 py-2 w-full sm:w-1/3">

            {{-- 資産名検索 --}}
            <input type="text" name="keyword" value="{{ request('keyword') }}"
            placeholder="資産名で検索" class="border rounded px-4 py-2 w-full sm:w-1/3">

            {{-- カテゴリ選択 --}}
            <select name="category_id" class="border rounded px-4 py-2 w-full sm:w-1/4">
                <option value="">カテゴリで検索</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" @selected(request('category_id') == $category->id)>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
            {{-- 状態選択 --}}
            <select name="status" class="border rounded px-4 py-2 w-full sm:w-1/4">
                <option value="">ステータスで検索</option>
                @foreach(\App\Enums\AssetStatus::cases() as $status)
                <option value="{{ $status->value }}" @selected(request('status') == $status->value)>
                    {{ $status->value }}</option>
                @endforeach
            </select>
            {{-- ユーザー選択 --}}
            <select name="user_id" class="border rounded px-4 py-2 w-full sm:w-1/4">
                <option value="">ユーザーで検索</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}" @selected(request('user_id') == $user->id)>
                        {{ $user->name }}
                    </option>
                @endforeach
            </select>

            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                検索
            </button>
            <a href="#" id="resetBtn"
                class="ml-2 bg-gray-300 text-gray-800 px-4 py-2 rounded hover:bg-gray-400">
                リセット
            </a>
        </form>
    </div>
    <div id="result">
        <p class="mb-2 text-sm text-gray-600">
            {{ $assets->total() }} 件が見つかりました
        </p>
<!-- 一覧 -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
            <table class="table-auto w-full">
                <thead>
                    <tr>
                        <th class="px-4 py-2">カテゴリ名</th>
                        <th class="px-4 py-2">資産名</th>
                        <th class="px-4 py-2">番号</th>
                        <th class="px-4 py-2">取得日</th>
                        <th class="px-4 py-2">状態</th>
                        <th class="px-4 py-2">使用者</th>
                        <th class="px-4 py-2">貸出登録</th>
                        <th class="px-4 py-2">返却登録</th>
                        <th class="px-4 py-2">そのほか</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($assets as $asset)
                        <tr>
                            <td class="border px-4 py-2">{{ $asset->category->name ?? '未分類' }}</td>
                            <td class="border px-4 py-2">{{ $asset->name }}</td>
                            <td class="border px-4 py-2">{{ $asset->asset_number }}</td>
                            <td class="border px-4 py-2">{{ $asset->acquisition_date }}</td>
                            <td class="border px-4 py-2">{{ $asset->status }}</td>
                            <td class="border px-4 py-2">{{ $asset->user->name ?? '未割当て' }}</td>
                            <td class="border px-4 py-2">
                                @if (!$asset->isInUse())
                                <form action="{{ route('loan.borrow') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="asset_id" value="{{ $asset->id }}">
                                    <button type="submit" class="bg-sky-300 p-2 rounded">貸出</button>
                                </form>
                                @else
                                    <button class="bg-gray-300 text-gray-500 p-2 cursor-not-allowed rounded" disabled>貸出中</button>
                                @endif
                            </td>
                            <td class="border px-4 py-2">
                                @if ($asset->isInUse())
                                <form action="{{ route('loan.return') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="asset_id" value="{{ $asset->id }}">
                                    <button type="submit" class="bg-emerald-200 p-2 rounded">返却</button>
                                </form>
                                @else
                                <button class="bg-gray-300 text-gray-500 p-2 cursor-not-allowed rounded" disabled>返却不可</button>
                                @endif
                            </td>
                            <td class="border px-4 py-2">
                                <a href="{{ route('assets.edit', $asset) }}" class="text-blue-600 hover:underline">編集</a>
                                <form action="{{ route('assets.destroy', $asset) }}" method="POST" class="inline-block ml-2">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-red-600 hover:underline" onclick="return confirm('削除してもよろしいですか？')">削除</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="mt-4">
                <!-- ページネーション -->
                {{ $assets->appends(request()->query())->links() }}
            </div>
        </div>
    </div>

    </div>
</x-app-layout>
