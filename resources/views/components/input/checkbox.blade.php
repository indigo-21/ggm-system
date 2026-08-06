@props([
    'name',
    'label' => '',
    'class' => '',
    'checked' => false,
])

<div class="checkbox w-25">
    <input
        id="{{ $name }}"
        name="{{ $name }}"
        type="checkbox"
        value="{{ $checked }}"
        class="input-checkbox {{ $class }}"
        @if($checked) checked @endif
        aria-label="{{ $label ?: $name }}"
    >
    <label for="{{ $name }}" class="ml-2">
        {{ $label }}
    </label>
</div>
