@props(['label', 'value', 'class' => ''])

<div class="{{ $class }}">
    <x-input-label :value="$label" />
    <div class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm bg-gray-50 text-gray-900">
        {{ $value }}
    </div>
</div>
