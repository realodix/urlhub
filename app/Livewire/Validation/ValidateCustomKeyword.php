<?php

namespace App\Livewire\Validation;

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
        $settings = app(\App\Settings\GeneralSettings::class);
        $minLen = $settings->custom_keyword_min_length;
        $maxLen = $settings->custom_keyword_max_length;

        return [
            'keyword' => [
                "min:{$minLen}", "max:{$maxLen}", 'unique:App\Models\Url', 'lowercase:field',
                new \App\Rules\AlphaNumHyphen,
                new \App\Rules\NotBlacklistedKeyword,
            ],
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
