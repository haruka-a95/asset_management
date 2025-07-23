<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            詳細
        </h2>
    </x-slot>

    <x-flash-message />

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
                        <th class="px-4 py-2">操作</th>
                    </tr>
                </thead>
                <tbody>
                        <tr>
                            <td class="border px-4 py-2">{{ $asset->category->name ?? '未分類' }}</td>
                            <td class="border px-4 py-2">{{ $asset->name }}</td>
                            <td class="border px-4 py-2">{{ $asset->asset_number }}</td>
                            <td class="border px-4 py-2">{{ $asset->acquisition_date }}</td>
                            <td class="border px-4 py-2">{{ $asset->status }}</td>
                            <td class="border px-4 py-2">{{ $asset->user->name ?? '未割当て' }}</td>
                            <td class="border px-4 py-2">
                                <a href="{{ route('assets.edit', $asset) }}" class="text-blue-600 hover:underline">編集</a>
                                <form action="{{ route('assets.destroy', $asset) }}" method="POST" class="inline-block ml-2">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-red-600 hover:underline" onclick="return confirm('削除してもよろしいですか？')">削除</button>
                                </form>
                            </td>
                        </tr>
                </tbody>
            </table>
        </div>
</x-app-layout>