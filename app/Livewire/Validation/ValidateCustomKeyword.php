<?php

namespace App\Livewire\Validation;

use App\Rules\LinkRules;
use Livewire\Component;

/**
 * - https://livewire.laravel.com/docs/validation#real-time-validation
 * - resources\views\frontend\homepage.blade.php
 * - resources\views\livewire\validation\validate-custom-keyword.blade.php
 */
class ValidateCustomKeyword extends Component
{
    /**
     * @var string
     */
    public $keyword;

    /**
     * @return array<string, array<string|object>>
     */
    public function rules()
    {
        return [
            'keyword' => [...LinkRules::customKeyword()],
        ];
    }

    /**
     * @param string $propertyName
     * @return void
     */
    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function render()
    {
        return view('livewire.validation.validate-custom-keyword');
    }
}
