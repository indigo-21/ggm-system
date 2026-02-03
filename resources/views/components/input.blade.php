@props([
        'type'          => 'text', 
        'name', 'value' => '',
        'id'            => '', 
        'label'         => false, 
        'required'      => false, 
        'error'         => null,
        'checked'       => false,
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
                @if($label) placeholder="Enter {{ $label }}" @endif
                @if($required) required @endif
                @if($disabled) disabled @endif
                @if($readonly) readonly @endif
                @if($uniqueid) data-unique-id="{{ $uniqueid }}" @endif
                @if($inputformat) data-input-format="{{ $inputformat }}" @endif
                autocomplete="off"
            >
        
            <label id="{{ $name }}-error" class="error">{{$error}}</label>
        </div>
    @elseif ($type === "radio")
        <div class="radio inlineblock">
            <input 
                type="{{ $type }}"
                name="{{ $name }}"
                id="{{ $id }}"
                value="{{ old($name, $value) }}"
                class="{{ $class }}"
                @if($required) required @endif
                @if($checked) checked @endif
            >
            @if ($label)
                <label class="mr-2" for="{{ $id }}">{{ $label }}</label>
            @endif
        </div>
    @elseif($type === "textarea")
        <label for="{{ $name }}">{{ $label }}</label>
        <div class="form-line">
                <textarea 
                name="{{ $name }}"
                id="{{ $name }}"
                class="form-control no-resize {{ $class }}"
                @if($label) placeholder="Enter {{ $label }}" @endif
                @if($required) required @endif
                @if($disabled) disabled @endif
                @if($uniqueid) data-unique-id="{{ $uniqueid }}" @endif
                @if($inputformat) data-input-format="{{ $inputformat }}" @endif
                rows="5">{{ old($name, $value) }}</textarea>
        </div>
    @endif






