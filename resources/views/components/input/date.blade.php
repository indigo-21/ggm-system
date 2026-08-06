@props([
    'type' => 'date',
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

@php
    $rawValue = old($name, $value);
    $inputValue = '';
    $inputPeriod = 'AM';
    $hasAmPm = $class === 'datetime-am-pm';

    if (!empty($rawValue)) {
        try {
            if ($hasAmPm) {
                // Value format: "January 15, 2026 PM" or "2026-01-15 14:00:00"
                $cleanValue = trim(str_replace(['AM', 'PM'], '', $rawValue));
                $inputValue = date('Y-m-d', strtotime($cleanValue));
                $inputPeriod = str_contains($rawValue, 'PM') ? 'PM' : 'AM';
            } else {
                // Standard date: try to parse into Y-m-d
                $timestamp = strtotime($rawValue);
                $inputValue = $timestamp ? date('Y-m-d', $timestamp) : $rawValue;
            }
        } catch (\Throwable $e) {
            // Fallback: keep original value if parsing fails
            $inputValue = $rawValue;
        }
    }

    $inputId = $name;
    $periodName = $name . '_period';
    $errorMessage = $error ?? ($label ? $label . ' is required' : '');
@endphp

<div class="form-group form-float">
    @if ($label)
        <label for="{{ $inputId }}">{{ $label }}</label>
    @endif

    <div class="d-flex align-items-center" style="gap: 0.5rem;">
        <input
            type="date"
            name="{{ $name }}"
            id="{{ $inputId }}"
            value="{{ $inputValue }}"
            class="form-control {{ $class }}"
            @if($required) required aria-required="true" @endif
            @if($disabled) disabled aria-disabled="true" @endif
            @if($readonly) readonly @endif
            @if($uniqueid) data-unique-id="{{ $uniqueid }}" @endif
            @if($inputformat) data-input-format="{{ $inputformat }}" @endif
            autocomplete="off"
            aria-label="{{ $label ?: $name }}"
        >

        @if($hasAmPm)
            <select
                name="{{ $periodName }}"
                id="{{ $periodName }}"
                class="form-control"
                style="max-width: 50px;"
                @if($disabled) disabled aria-disabled="true" @endif
                aria-label="{{ $label ? $label . ' period' : 'AM/PM' }}"
            >
                <option value="AM" {{ $inputPeriod === 'AM' ? 'selected' : '' }}>AM</option>
                <option value="PM" {{ $inputPeriod === 'PM' ? 'selected' : '' }}>PM</option>
            </select>
        @endif
    </div>

    @if($errorMessage)
        <span class="invalid-feedback" role="alert">{{ $errorMessage }}</span>
    @endif
</div>
