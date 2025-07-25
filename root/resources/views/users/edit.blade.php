<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 leading-tight">
            ユーザー編集
        </h2>
    </x-slot>

    <div class="max-w-2xl mx-auto p-6 bg-white shadow mt-6 rounded">
        <form method="POST" action="{{ route('users.update', $user->id) }}">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="block text-sm font-medium">名前</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" class="mt-1 block w-full rounded border-gray-300" required>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium">メール</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" class="mt-1 block w-full rounded border-gray-300" required>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium">部署</label>
                <select name="department_id" class="mt-1 block w-full rounded border-gray-300">
                    <option value="">選択してください</option>
                    @foreach($departments as $department)
                        <option value="{{ $department->id }}"
                            @if(old('department_id', $user->department_id) == $department->id) selected @endif>
                            {{ $department->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex justify-end">
                <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded" type="submit">
                    更新
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
