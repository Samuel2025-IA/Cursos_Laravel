@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'font-medium text-sm text-[#2f9f37] dark:text-[#2f9f37]/80']) }}>
        {{ $status }}
    </div>
@endif
