<nav class="bg-gray-800 p-4 text-white block">
    <ul class="flex space-x-4">
        @foreach ($menu as $item)
            <li>
                <a href="{{ $item['route'] }}"
                    class="@if(request()->url() === $item['route']) font-bold underline @endif">
                        {{ $item['title'] }}
                </a>
            </li>
        @endforeach
    </ul>
</nav>