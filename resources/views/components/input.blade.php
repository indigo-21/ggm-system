@props([
        'type'          => 'text', 
        'name', 'value' => '',
        'id'            => '', 
        'label'         => false, 
        'required'      => false, 
        'error'         => null,
        'checked'       => false,
        'multiple'      => false,
    ])

   
    @if($type === "text" || $type === "password" || $type === "date" || $type === "file")
        <div class="form-group form-float">
            @if ($label)
                <label class="{{ $class }}" for="{{ $name }}">{{ $label }}</label>
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
                @if($multiple) multiple="multiple" @endif
                autocomplete="off"
            >
            {{-- <label id="{{ $name }}-error" class="error">{{$error}}</label> --}}
            <span class="invalid-feedback">{{$error ?? $label." is required"}}</span>
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
                rows="{{$rows}}">{!! old($name, $value) !!}</textarea>
                <span class="invalid-feedback">{{$error ?? $label." is required"}}</span>
        </div>
    @elseif($type === "checkbox")
        <div class="checkbox w-25">
            <input id="{{ $name }}"  name="{{ $name }}" type="checkbox" value="{{ $value }}" class="{{ $class }}">
            <label for="{{ $name }}" class="ml-2">
                    {{ $label }}
            </label>
        </div>
    @endif






