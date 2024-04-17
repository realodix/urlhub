<?php

namespace App\Livewire;

use Livewire\Component;

/**
 * - https://livewire.laravel.com/docs/validation#real-time-validation
 * - resources\views\frontend\homepage.blade.php
 * - resources\views\livewire\url-check.blade.php
 */
class UrlCheck extends Component
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
        return [
            'keyword' => [
                'min:2', 'max:20', 'unique:App\Models\Url', 'lowercase:field',
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
        return view('livewire.url-check');
    }
}
