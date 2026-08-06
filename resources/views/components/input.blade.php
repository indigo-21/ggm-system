@props([
    'type' => 'text',
    'name',
    'value' => '',
    'id' => '',
    'label' => false,
    'class' => '',
    'required' => false,
    'disabled' => false,
    'readonly' => false,
    'multiple' => false,
    'error' => null,
    'checked' => false,
    'inputformat' => null,
    'uniqueid' => null,
    'rows' => 5,
])

@if($type === 'radio')
    <x-input.radio
        :name="$name"
        :value="$value"
        :id="$id"
        :label="$label"
        :class="$class"
        :required="$required"
        :checked="$checked"
    />
@elseif($type === 'checkbox')
    <x-input.checkbox
        :name="$name"
        :label="$label"
        :class="$class"
        :checked="$checked"
    />
@elseif($type === 'textarea')
    <x-input.textarea
        :name="$name"
        :value="$value"
        :label="$label"
        :class="$class"
        :required="$required"
        :disabled="$disabled"
        :error="$error"
        :inputformat="$inputformat"
        :uniqueid="$uniqueid"
        :rows="$rows"
    />
@elseif($type === 'date' || $type === 'datetime-local' ) 
    <x-input.date
        :type="$type"
        :name="$name"
        :value="$value"
        :label="$label"
        :class="$class"
        :required="$required"
        :disabled="$disabled"
        :readonly="$readonly"
        :multiple="$multiple"
        :error="$error"
        :inputformat="$inputformat"
        :uniqueid="$uniqueid" />

@else
    {{-- text, password, file --}}
    <x-input.text
        :type="$type"
        :name="$name"
        :value="$value"
        :label="$label"
        :class="$class"
        :required="$required"
        :disabled="$disabled"
        :readonly="$readonly"
        :multiple="$multiple"
        :error="$error"
        :inputformat="$inputformat"
        :uniqueid="$uniqueid"
    />
@endif
