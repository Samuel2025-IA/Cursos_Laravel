<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-[#2f9f37] border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-[#2f9f37]/90 focus:bg-[#2f9f37]/90 active:bg-[#2f9f37]/80 focus:outline-none focus:ring-2 focus:ring-[#2f9f37] focus:ring-offset-2 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
