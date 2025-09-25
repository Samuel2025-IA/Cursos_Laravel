@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-1 pt-1 border-b-2 border-[#2f9f37] text-sm font-medium leading-5 text-[#2f9f37] focus:outline-none focus:border-[#2f9f37] transition duration-150 ease-in-out'
            : 'inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-600 hover:text-[#2f9f37] hover:border-[#2f9f37]/30 focus:outline-none focus:text-[#2f9f37] focus:border-[#2f9f37]/30 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
