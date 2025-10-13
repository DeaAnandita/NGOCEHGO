@props(['route', 'color' => 'blue', 'icon' => '📦', 'title'])

<a href="{{ route('master.index', $route) }}"
   class="group bg-gradient-to-br from-{{ $color }}-100 to-{{ $color }}-50 hover:from-{{ $color }}-200 hover:to-{{ $color }}-100 border border-{{ $color }}-200 p-3 rounded-xl shadow-sm transition-all duration-150 hover:-translate-y-0.5">
    <div class="flex flex-col items-center text-center">
        <div class="bg-{{ $color }}-500 text-white p-2 rounded-full mb-2 shadow-sm group-hover:scale-105 transition text-sm">
            {{ $icon }}
        </div>
        <h4 class="text-sm font-semibold text-{{ $color }}-800 group-hover:text-{{ $color }}-900 leading-tight">
            {{ $title }}
        </h4>
    </div>
</a>
