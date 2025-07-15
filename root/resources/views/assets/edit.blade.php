<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            資産編集
        </h2>
    </x-slot>

    <div class="py-12 max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white p-6 shadow-md sm:rounded-lg">
            <form method="POST" action="{{ route('assets.update', $asset) }}">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">資産名</label>
                    <input type="text" name="name" class="w-full border-gray-300 rounded-md shadow-sm" value="{{ old('name', $asset->name) }}" required>
                    @error('name') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">資産番号</label>
                    <input type="text" name="asset_number" class="w-full border-gray-300 rounded-md shadow-sm" value="{{ old('asset_number', $asset->asset_number) }}" required>
                    @error('asset_number') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">取得日</label>
                    <input type="date" name="acquisition_date" class="w-full border-gray-300 rounded-md shadow-sm" value="{{ old('acquisition_date', $asset->acquisition_date) }}" required>
                    @error('acquisition_date') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">価格</label>
                    <input type="number" name="price" class="w-full border-gray-300 rounded-md shadow-sm" value="{{ old('price', $asset->price) }}" required>
                    @error('price') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">設置場所</label>
                    <input type="text" name="location" class="w-full border-gray-300 rounded-md shadow-sm" value="{{ old('location', $asset->location) }}">
                    @error('location') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">状態</label>
                    <select name="status" class="w-full border-gray-300 rounded-md shadow-sm" required>
                        <option value="">選択してください</option>
                        <option value="使用中" @selected($asset->status == '使用中')>使用中</option>
                        <option value="保管中" @selected($asset->status == '保管中')>保管中</option>
                        <option value="廃棄済" @selected($asset->status == '廃棄済')>廃棄済</option>
                    </select>
                    @error('status') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
                </div>

                <div class="flex justify-end">
                    <a href="{{ route('assets.index') }}" class="mr-4 text-gray-600 hover:underline">戻る</a>
                    <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">更新</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
