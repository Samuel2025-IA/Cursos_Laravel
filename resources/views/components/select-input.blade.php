@props(['disabled' => false])

<select @disabled($disabled) {{ $attributes->merge(['class' => 'border-gray-300 focus:border-[#2f9f37] focus:ring-[#2f9f37] rounded-md shadow-sm']) }}>
    {{ $slot }}
</select>
