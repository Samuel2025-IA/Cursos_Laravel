@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-gray-300 bg-white text-gray-900 focus:border-[#2f9f37] focus:ring-[#2f9f37] rounded-md shadow-sm']) }}>
