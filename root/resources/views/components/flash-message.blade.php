@if (session('success'))
    <div class="mb-4 p-4 bg-green-100 text-green-800 border border-green-300 rounded">
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div class="mb-4 p-4 bg-red-100 text-red-800 border border-red-300 rounded">
        {{ session('error') }}
    </div>
@endif

@if (session('info'))
    <div class="mb-4 p-4 bg-blue-100 text-blue-800 border border-blue-300 rounded">
        {{ session('info') }}
    </div>
@endif

@if (session('warning'))
    <div class="mb-4 p-4 bg-yellow-100 text-yellow-800 border border-yellow-300 rounded">
        {{ session('warning') }}
    </div>
@endif
