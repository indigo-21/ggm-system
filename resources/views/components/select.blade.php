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
    'timestamp' => false,
    'has_timestamp' => false,
    'error' => null])

<div class="form-group form-float">
    <label for="{{ $name }}">{{ $label }}</label>

    @php
        $datetime = "";
        if($timestamp){
            $datetime = \Carbon\Carbon::parse($timestamp)->format('F d, Y h:i A');
        }
    @endphp

    <span class="span-timestamp text-muted small"> {{ $timestamp ? $datetime : '' }}</span>
    <input type="hidden" name="{{ $name }}_timestamp" value="{{ $timestamp ? $datetime : '' }}">    

    @if (!$hasinput)
        <select class="form-control {{ $class }} {{$has_timestamp ? "select-timestamp" : ""}}"
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