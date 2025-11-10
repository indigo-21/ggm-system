@props(
[
    'name', 'value' => '', 
    'label' => '', 
    'class' => '', 
    'required' => false, 
    'disabled' => false, 
    'multiple' => false,
    'search'   => false, 
    'error' => null])

<div class="form-group form-float">
        <label for="{{ $name }}">{{ $label }}</label>
        <select class="form-control {{ $class }}"
            name="{{ $name }}" id="{{ $name }}"
            @if($required) required @endif
            @if($disabled) disabled @endif
            @if($multiple) multiple @endif
            @if($search) data-live-search="true" @endif>
           {{$slot}}
        </select>
    
        <label id="{{ $name }}-error" class="error" for="{{ $name }}">{{$error}}</label>
</div>