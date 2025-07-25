<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            資産登録
        </h2>
    </x-slot>

    <div class="py-12 max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white p-6 shadow-md sm:rounded-lg">
            <form method="POST" action="{{ route('assets.store') }}">
                @csrf
                <div class="flex gap-5 space-between items-center">
                    <div class="mb-4">
                        <label for="category_id">カテゴリ</label>
                        <select name="category_id" id="categoryId" class="rounded-md shadow-sm">
                            <option value="">選択してください</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4 flex gap-2 align-center">
                        <label class="text-sm font-medium text-gray-700">資産番号(自動割り当て)</label>
                        <!-- JSで取得 -->
                        <input type="text" name="asset_number" class="border-gray-300 bg-gray-200 rounded-md shadow-sm" value="{{ old('asset_number') }}" readonly required>
                        @error('asset_number') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
                    </div>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">資産名</label>
                    <input type="text" name="name" class="w-full border-gray-300 rounded-md shadow-sm" value="{{ old('name') }}" required>
                    @error('name') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">取得日</label>
                    <input type="date" name="acquisition_date" class="w-full border-gray-300 rounded-md shadow-sm" value="{{ old('acquisition_date') }}" required>
                    @error('acquisition_date') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">価格</label>
                    <input type="number" name="price" class="w-full border-gray-300 rounded-md shadow-sm" value="{{ old('price') }}" required>
                    @error('price') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">使用場所</label>
                    <select name="department_id" class="w-full border-gray-300 rounded-md shadow-sm">
                        <option value="">選択してください</option>
                        @foreach($departments as $department)
                            <option value="{{ $department->id }}">
                                    {{ $department->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('status') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
                </div>


                <div class="mb-4">
                    <select name="user_id" class="rounded-md shadow-sm">
                        <option value="">-- 使用者を選択 --</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}">
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">状態</label>
                    <select name="status" class="w-full border-gray-300 rounded-md shadow-sm" required>
                        <option value="">選択してください</option>
                            @foreach(\App\Enums\AssetStatus::cases() as $status)
                            <option value="{{ $status->value }}" @selected(old('status') === $status->value)>
                                {{ $status->value }}
                            </option>
                        @endforeach
                    </select>
                    @error('status') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
                </div>

                <div class="flex justify-end">
                    <a href="{{ route('assets.index') }}" class="mr-4 text-gray-600 hover:underline">戻る</a>
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">登録</button>
                </div>
            </form>
        </div>
    </div>



</x-app-layout>
