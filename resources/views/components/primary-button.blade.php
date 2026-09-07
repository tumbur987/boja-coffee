<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-6 py-3 bg-gradient-to-r from-[#4a2c2a] to-[#6b4226] border border-transparent rounded-xl font-semibold text-sm text-white tracking-wide hover:from-[#6b4226] hover:to-[#4a2c2a] focus:outline-none focus:ring-2 focus:ring-[#d4a574] focus:ring-offset-2 transition-all duration-300 hover:-translate-y-0.5 hover:shadow-lg']) }}>
    {{ $slot }}
</button>
