@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full ps-3 pe-4 py-2 border-l-4 border-[#2f9f37] text-start text-base font-medium text-[#2f9f37] bg-[#2f9f37]/5 focus:outline-none focus:text-[#2f9f37]/80 focus:bg-[#2f9f37]/10 focus:border-[#2f9f37] transition duration-150 ease-in-out'
            : 'block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-gray-600 hover:text-[#2f9f37] hover:bg-[#2f9f37]/5 hover:border-[#2f9f37]/30 focus:outline-none focus:text-[#2f9f37] focus:bg-[#2f9f37]/5 focus:border-[#2f9f37]/30 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
