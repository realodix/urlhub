<?php

namespace App\Http\Livewire;

use Livewire\Component;

class UrlCheck extends Component
{
    public $keyword;

    protected $rules = [
        'keyword' => 'required|unique:App\Models\Url',
    ];

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function render()
    {
        return view('livewire.url-check');
    }
}
