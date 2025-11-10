@props(['active'])

@php
$classes = ($active ?? false)
            ? 'active open'
            : '';
@endphp

<li {{ $attributes->merge(['class' => $classes]) }} >
    {{ $slot }}
</li>   