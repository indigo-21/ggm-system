@props(
[
    'name', 'value' => '', 
    'label' => '', 
    'class' => '', 
    'required' => false, 
    'disabled' => false, 
    'multiple' => false,
    'search'   => false,
    'hasinput' => false,
    'placeholder' => "" ,
    'error' => null])

<div class="form-group form-float">
    <label for="{{ $name }}">{{ $label }}</label>

    @if (!$hasinput)
        <select class="form-control {{ $class }}"
            name="{{ $name }}" id="{{ $name }}"
            @if($required) required @endif
            @if($disabled) disabled @endif
            @if($multiple) multiple @endif
            @if($search) data-live-search="true" @endif>
           {{$slot}}
        </select>
    @else
        <input type="text" class="form-control datalist-input" list="{{ $name }}" name="{{ $name }}" placeholder="{{$placeholder}}">
        <datalist id="{{ $name }}">{{ $slot }}</datalist>
    @endif
        
    <label id="{{ $name }}-error" class="error" for="{{ $name }}">{{$error}}</label>
</div>