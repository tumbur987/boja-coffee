@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-2 border-[#f5e6d3] focus:border-[#d4a574] focus:ring-4 focus:ring-[#d4a574]/15 rounded-xl shadow-sm bg-[#faf3eb] focus:bg-white text-[#2c1810] placeholder-[#8b7355]/60 transition-all duration-200']) }}>
