@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-semibold text-sm text-[#2c1810]']) }}>
    {{ $value ?? $slot }}
</label>
