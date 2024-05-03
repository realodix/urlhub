<?php

namespace App\Livewire;

use Livewire\Component;

/**
 * - https://livewire.laravel.com/docs/validation#real-time-validation
 * - resources\views\frontend\homepage.blade.php
 * - resources\views\livewire\custom-keyword-validator.blade.php
 */
class CustomKeywordValidator extends Component
{
    /**
     * @var string
     */
    public $keyword;

    /**
     * @return array
     */
    public function rules()
    {
        $minLen = config('urlhub.custom_keyword_min_length');
        $maxLen = config('urlhub.custom_keyword_max_length');

        return [
            'keyword' => [
                "min:$minLen", "max:$maxLen", 'unique:App\Models\Url', 'lowercase:field',
                new \App\Rules\StrAlphaUnderscore,
                new \App\Rules\Url\KeywordBlacklist,
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
        return view('livewire.custom-keyword-validator');
    }
}
