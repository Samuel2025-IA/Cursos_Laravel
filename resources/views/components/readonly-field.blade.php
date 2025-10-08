@props(['label', 'value', 'class' => ''])

<div class="{{ $class }}">
    <x-input-label :value="$label" class="text-sm font-medium text-gray-700 mb-2" />
    <div class="block w-full px-4 py-3 border border-gray-200 rounded-lg shadow-sm bg-white text-gray-900 font-medium">
        {{ $value }}
    </div>
</div>
