@props([
    'name',
    'value' => '',
    'id' => '',
    'label' => false,
    'class' => '',
    'required' => false,
    'checked' => false,
])

<div class="radio inlineblock">
    <input
        type="radio"
        name="{{ $name }}"
        id="{{ $id }}"
        value="{{ old($name, $value) }}"
        class="{{ $class }}"
        @if($required) required @endif
        @if($checked) checked @endif
        aria-label="{{ $label ?: $name }}"
    >
    @if ($label)
        <label class="mr-2" for="{{ $id }}">{{ $label }}</label>
    @endif
</div>
