@props(['active', 'icon' => ''])

@php
$classes = ($active ?? false)
            ? 'group flex items-center px-4 py-3 text-sm font-medium text-white bg-indigo-600 rounded-lg shadow-md transition-all duration-200'
            : 'group flex items-center px-4 py-3 text-sm font-medium text-slate-300 rounded-lg hover:bg-slate-800 hover:text-white transition-all duration-200';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    <span class="mr-3 text-lg transition-transform duration-200 group-hover:scale-110">{{ $icon }}</span>
    <span>{{ $slot }}</span>
</a>
