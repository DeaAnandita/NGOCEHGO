@props([
    'route',
    'title',
    'desc',
    'icon',   // emoji dari data (tetap)
    'color' => 'blue'
])

@php
// ===== MAP EMOJI → HEROICON (100% VALID) =====
$iconMap = [
    '🕌' => 'building-library',
    '💍' => 'heart',
    '🏠' => 'home',
    '🌾' => 'sparkles',
    '🐄' => 'archive-box',
    '🐟' => 'archive-box',
    '🔥' => 'fire',
    '🗑️' => 'trash',
    '💧' => 'beaker',
    '🏘️' => 'home-modern',
    '🏡' => 'home',
    '👪' => 'user-group',
    '👨‍👩‍👧‍👦' => 'users',
    '📜' => 'document-text',
    '💉' => 'shield-check',
    '📦' => 'archive-box',
    '🧑‍💼' => 'briefcase',
    '✍️' => 'pencil',
    '🏗️' => 'building-office-2',
    '⚔️' => 'exclamation-triangle',
    '👶' => 'face-smile',
    '🤰' => 'heart',
    '🏫' => 'academic-cap',
    '📋' => 'clipboard-document-list',
    '🏥' => 'building-office',
    '⛏️' => 'wrench-screwdriver',
    '🏢' => 'building-office',
    '♿' => 'hand-raised',          // FIX
    '♂️♀️' => 'users',
    '🧱' => 'square-3-stack-3d',
    '🏛️' => 'banknotes',
    '🆔' => 'identification',
    '🏬' => 'building-storefront',
    '💰' => 'banknotes',
    '📤' => 'arrow-up-tray',
    '📥' => 'arrow-down-tray',
    '🚽' => 'home',
    '⚕️' => 'heart',
    '💡' => 'light-bulb',
    '🔌' => 'bolt',
    '🧾' => 'document',
    '📄' => 'document-text',
    '📅' => 'calendar-days',
    '📍' => 'map-pin',
    '📊' => 'chart-bar',
    '⚖️' => 'scale',
    '🧮' => 'calculator',
    '⏳' => 'clock',
    '📌' => 'bookmark',
    '🔄' => 'arrow-path',
    '🎯' => 'adjustments-horizontal',
    '🎓' => 'academic-cap',
    '✅' => 'check-circle',
    '👷' => 'user',
    '🪧' => 'tag',
];

// fallback SUPER AMAN
$heroIcon = $iconMap[$icon] ?? 'squares-2x2';

// warna (aman Tailwind)
$colors = [
    'blue' => 'from-blue-100 to-blue-50 hover:from-blue-200 border-blue-200 text-blue-700',
    'green' => 'from-green-100 to-green-50 hover:from-green-200 border-green-200 text-green-700',
    'indigo' => 'from-indigo-100 to-indigo-50 hover:from-indigo-200 border-indigo-200 text-indigo-700',
    'yellow' => 'from-yellow-100 to-yellow-50 hover:from-yellow-200 border-yellow-200 text-yellow-700',
    'red' => 'from-red-100 to-red-50 hover:from-red-200 border-red-200 text-red-700',
    'teal' => 'from-teal-100 to-teal-50 hover:from-teal-200 border-teal-200 text-teal-700',
    'orange' => 'from-orange-100 to-orange-50 hover:from-orange-200 border-orange-200 text-orange-700',
    'purple' => 'from-purple-100 to-purple-50 hover:from-purple-200 border-purple-200 text-purple-700',
    'cyan' => 'from-cyan-100 to-cyan-50 hover:from-cyan-200 border-cyan-200 text-cyan-700',
    'pink' => 'from-pink-100 to-pink-50 hover:from-pink-200 border-pink-200 text-pink-700',
    'lime' => 'from-lime-100 to-lime-50 hover:from-lime-200 border-lime-200 text-lime-700',
    'emerald' => 'from-emerald-100 to-emerald-50 hover:from-emerald-200 border-emerald-200 text-emerald-700',
];

$theme = $colors[$color] ?? $colors['blue'];
@endphp

<a href="{{ route('master.index', $route) }}"
   class="group bg-gradient-to-br {{ $theme }}
          border p-6 rounded-2xl shadow
          transition-all duration-200 hover:-translate-y-1">

    <div class="flex flex-col items-center text-center">

        {{-- ICON --}}
        <div class="bg-white rounded-xl p-3 mb-3 shadow-sm">
            @if (view()->exists('components.heroicon-o-' . $heroIcon))
                <x-dynamic-component
                    :component="'heroicon-o-' . $heroIcon"
                    class="w-6 h-6"
                />
            @else
                <x-heroicon-o-squares-2x2 class="w-6 h-6" />
            @endif

        </div>

        {{-- TITLE --}}
        <h4 class="text-lg font-semibold">
            {{ $title }}
        </h4>

        {{-- DESC --}}
        <p class="text-gray-600 text-sm mt-1">
            {{ $desc }}
        </p>
    </div>
</a>
