<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            貸出記録
        </h2>
    </x-slot>

    <div class="container">
        <x-flash-message />
        <h2 class="text-xl font-bold mb-4">貸出履歴</h2>

        <table class="w-full table-auto border">
            <thead>
                <tr class="bg-gray-100">
                    <th class="px-4 py-2 border">ユーザー名</th>
                    <th class="px-4 py-2 border">資産名</th>
                    <th class="px-4 py-2 border">貸出日</th>
                    <th class="px-4 py-2 border">返却日</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($logs as $log)
                <tr>
                    <td class="px-4 py-2 border">{{ $log->user->name }}</td>
                    <td class="px-4 py-2 border">{{ $log->asset->name }}</td>
                    <td class="px-4 py-2 border">{{ $log->loaned_at }}</td>
                    <td class="px-4 py-2 border">
                        {{ $log->returned_at ?? '-' }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-4">
            {{ $logs->links() }}
        </div>
    </div>

</x-app-layout>