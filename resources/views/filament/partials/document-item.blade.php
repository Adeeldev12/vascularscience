@props(['label', 'path', 'icon', 'color' => 'gray'])

@php
    $colors = [
        'blue' => 'border-blue-200 bg-blue-50 hover:bg-blue-100',
        'green' => 'border-green-200 bg-green-50 hover:bg-green-100',
        'orange' => 'border-orange-200 bg-orange-50 hover:bg-orange-100',
        'purple' => 'border-purple-200 bg-purple-50 hover:bg-purple-100',
        'red' => 'border-red-200 bg-red-50 hover:bg-red-100',
        'yellow' => 'border-yellow-200 bg-yellow-50 hover:bg-yellow-100',
        'indigo' => 'border-indigo-200 bg-indigo-50 hover:bg-indigo-100',
        'gray' => 'border-gray-200 bg-gray-50 hover:bg-gray-100'
    ];
    $colorClass = $colors[$color] ?? $colors['gray'];
@endphp

<div class="flex items-center justify-between p-3 border rounded-lg {{ $colorClass }} transition-colors duration-150">
    <div class="flex items-center space-x-3">
        <span class="text-lg">{{ $icon }}</span>
        <span class="font-medium text-gray-700">{{ $label }}</span>
    </div>

    <div class="flex items-center space-x-2">
        @if($path)
            <a href="{{ asset('storage/' . $path) }}"
               target="_blank"
               class="inline-flex items-center px-3 py-1 text-sm font-medium text-white bg-primary-600 rounded hover:bg-primary-700 transition-colors duration-150">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                </svg>
                View
            </a>
        @else
            <span class="inline-flex items-center px-3 py-1 text-sm font-medium text-red-700 bg-red-100 rounded">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
                Missing
            </span>
        @endif
    </div>
</div>
