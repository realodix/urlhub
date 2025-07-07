<?php

namespace App\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class FlatPickr extends Component
{
    public string $name;

    public string $id;

    public string $type;

    public string $value;

    public string $format;

    public string $placeholder;

    public array $options;

    protected static array $assets = ['alpine', 'flat-pickr'];

    public function __construct(
        string $name,
        ?string $id = null,
        ?string $value = '',
        string $format = 'Y-m-d H:i',
        ?string $placeholder = null,
        array $options = [],
    ) {
        $this->name = $name;
        $this->id = $id ?? $name;
        $this->type = 'text';
        $this->value = old($name, $value ?? '');

        $this->format = $format;
        $this->placeholder = $placeholder ?? $format;
        $this->options = $options;
    }

    public function options(): array
    {
        return array_merge([
            'dateFormat' => $this->format,
            'altInput' => true,
            'enableTime' => true,
        ], $this->options);
    }

    public function jsonOptions(): string
    {
        return json_encode((object) $this->options());
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|\Closure|string
    {
        return view('components.flat-pickr');
    }
}
