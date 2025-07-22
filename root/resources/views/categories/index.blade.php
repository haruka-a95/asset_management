<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            カテゴリ
        </h2>
    </x-slot>

    <div class="max-w-4xl mx-auto px-4 py-8">
        <h1 class="text-2xl font-bold mb-6">カテゴリ一覧</h1>

        @if (session('success'))
            <div class="mb-4 p-4 bg-green-100 border border-green-300 text-green-800 rounded">
                {{ session('success') }}
            </div>
        @endif

        <div class="mb-4">
            <a href="{{ route('categories.create') }}" class="inline-block px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                + カテゴリを追加
            </a>
        </div>

        <div class="bg-white shadow rounded overflow-hidden">
            <table class="min-w-full">
                <thead class="bg-gray-100 text-gray-700">
                    <tr>
                        <th class="px-4 py-2 text-left">カテゴリ名</th>
                        <th class="px-4 py-2 text-right">操作</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($categories as $category)
                        <tr class="border-t">
                            <td class="px-4 py-2">{{ $category->name }}</td>
                            <td class="px-4 py-2 text-right space-x-2">
                                <a href="{{ route('categories.edit', $category) }}" class="text-sm text-indigo-600 hover:underline">編集</a>

                                <form action="{{ route('categories.destroy', $category) }}" method="POST" class="inline-block" onsubmit="return confirm('削除してもよろしいですか？')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-sm text-red-600 hover:underline">削除</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="px-4 py-4 text-center text-gray-500" colspan="2">カテゴリが登録されていません。</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>


</x-app-layout>
