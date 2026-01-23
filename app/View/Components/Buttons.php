<?php

namespace App\View\Components;

use Closure;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;

class Buttons extends Component
{
    /**
     * Create a new component instance.
     */
    public $type, $label;

    public function __construct($type, $label)
    {
        $this->type = $type;
        $this->label = $label;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render()
    {
        return view('components.buttons');
    }
}
