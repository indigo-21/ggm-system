@props([
    'name',
    'value' => '',
    'label' => false,
    'class' => '',
    'required' => false,
    'disabled' => false,
    'error' => null,
    'inputformat' => null,
    'uniqueid' => null,
    'rows' => 5,
])

@if ($label)
    <label for="{{ $name }}">{{ $label }}</label>
@endif
<div class="form-line">
    <textarea
        name="{{ $name }}"
        id="{{ $name }}"
        class="form-control no-resize {{ $class }}"
        @if($label) placeholder="Enter {{ $label }}" @endif
        @if($required) required aria-required="true" @endif
        @if($disabled) disabled aria-disabled="true" @endif
        @if($uniqueid) data-unique-id="{{ $uniqueid }}" @endif
        @if($inputformat) data-input-format="{{ $inputformat }}" @endif
        rows="{{ $rows }}"
    >{!! old($name, $value) !!}</textarea>
    <span class="invalid-feedback">{{ $error ?? ($label ? $label . ' is required' : '') }}</span>
</div>
