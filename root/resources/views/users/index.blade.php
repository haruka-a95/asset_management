<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            ユーザー一覧
        </h2>
    </x-slot>
    <div class="container mx-auto p-4">
        <h1 class="text-xl font-bold mb-4">ユーザー一覧</h1>

        <table class="table-auto w-full border">
            <thead>
                <tr class="bg-gray-200 text-left">
                    <th class="px-4 py-2 border">ID</th>
                    <th class="px-4 py-2 border">名前</th>
                    <th class="px-4 py-2 border">メール</th>
                    <th class="px-4 py-2 border">登録日時</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr class="border-b">
                        <td class="px-4 py-2 border">{{ $user->id }}</td>
                        <td class="px-4 py-2 border">{{ $user->name }}</td>
                        <td class="px-4 py-2 border">{{ $user->email }}</td>
                        <td class="px-4 py-2 border">{{ $user->created_at->format('Y-m-d') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-4">
            {{ $users->links() }}
        </div>
    </div>

</x-app-layout>