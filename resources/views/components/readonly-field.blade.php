@props(['label', 'value'])

<div>
    <x-input-label :value="$label" />
    <div class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-md shadow-sm bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-100">
        {{ $value }}
    </div>
</div>
