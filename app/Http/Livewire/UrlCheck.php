<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Rules\StrAlphaUnderscore;
class UrlCheck extends Component
{
    public $keyword;

    public function rules()
    {
        return [
            'keyword' => [
                'alpha_num', 'min:2', 'max:20', 'unique:App\Models\Url', 'lowercase:field', new StrAlphaUnderscore
            ]
        ];
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function render()
    {
        return view('livewire.url-check');
    }
}
