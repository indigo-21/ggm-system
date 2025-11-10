@props([
        'type'          => 'text', 
        'name', 'value' => '',
        'id'            => '', 
        'label'         => false, 
        'required'      => false, 
        'error'         => null
    ])


    @if($type === "text" || $type === "password")
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
                placeholder="Enter {{ $label }}"
                @if($required) required @endif
                @if($disabled) disabled @endif
                @if($uniqueid) data-unique-id="{{ $uniqueid }}" @endif
                @if($inputformat) data-input-format="{{ $inputformat }}" @endif
            >
        
            <label id="{{ $name }}-error" class="error" for="{{ $name }}">{{$error}}</label>
        </div>
    @elseif ($type === "radio")
        <div class="radio inlineblock">
            @if ($label)
                <label for="{{ $name }}">{{ $label }}</label>
            @endif
            <input 
                type="{{ $type }}"
                name="{{ $name }}"
                id="{{ $id }}"
                value="{{ old($name, $value) }}"
                class="{{ $class }}"
                @if($required) required @endif
            >
        </div>
    @endif






