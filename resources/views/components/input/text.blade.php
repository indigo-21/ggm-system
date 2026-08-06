@props([
    'type' => 'text',
    'name',
    'value' => '',
    'label' => false,
    'class' => '',
    'required' => false,
    'disabled' => false,
    'readonly' => false,
    'multiple' => false,
    'error' => null,
    'inputformat' => null,
    'uniqueid' => null,
])

<div class="form-group form-float">
    @if ($label)
        <label for="{{ $name }}">{{ $label }}</label>
    @endif
    <input
        type="{{ $type }}"
        name="{{ $name }}"
        id="{{ $name }}"
        value="{{ old($name, $value) }}"
        class="form-control {{ $class }}"
        @if($label) placeholder="Enter {{ $label }}" @endif
        @if($required) required aria-required="true" @endif
        @if($disabled) disabled aria-disabled="true" @endif
        @if($readonly) readonly @endif
        @if($uniqueid) data-unique-id="{{ $uniqueid }}" @endif
        @if($inputformat) data-input-format="{{ $inputformat }}" @endif
        @if($multiple) multiple="multiple" @endif
        autocomplete="off"
    >
    <span class="invalid-feedback">{{ $error ?? ($label ? $label . ' is required' : '') }}</span>
</div>
