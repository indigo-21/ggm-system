<?php

namespace App\View\Components;

use Closure;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;

class Input extends Component
{
  
    public function __construct(
        public string $label,
        public string $name,
        public string $type         = 'text',
        public bool $required       = false,
        public string $value        = "",
        public string $class        = "",
        public bool $disabled       = false,
        public string $uniqueid     = "",
        public string $inputformat  = "",
        public string $message      = "",
    ) {
        $this->label         = Str::headline($label); // auto-generate label if not passed (optional)
        $this->inputformat  = match(strtolower($inputformat)) {
                                'alphanumeric'          => '[a-zA-Z0-9\s]',
                                'numbersonly'           => '[0-9]',
                                'specialcharacter'      => '[a-zA-Z0-9!@#&()\-]',
                                default                 => '[a-zA-Z\s]',
                            };
    }

    public function render()
    {
        return view('components.input');
    }
}
