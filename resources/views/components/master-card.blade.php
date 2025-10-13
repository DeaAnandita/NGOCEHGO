@props(['route', 'color' => 'blue', 'icon' => '📦', 'title', 'desc'])

<a href="{{ route('master.index', $route) }}"
   class="group bg-gradient-to-br from-{{ $color }}-100 to-{{ $color }}-50 hover:from-{{ $color }}-200 hover:to-{{ $color }}-100 border border-{{ $color }}-200 p-6 rounded-2xl shadow transition-all duration-200 hover:-translate-y-1">
    <div class="flex flex-col items-center text-center">
        <div class="bg-{{ $color }}-500 text-white p-3 rounded-full mb-3 shadow-md group-hover:scale-110 transition">
            {{ $icon }}
        </div>
        <h4 class="text-lg font-semibold text-{{ $color }}-800 group-hover:text-{{ $color }}-900">{{ $title }}</h4>
        <p class="text-gray-600 text-sm mt-1">{{ $desc }}</p>
    </div>
</a>
