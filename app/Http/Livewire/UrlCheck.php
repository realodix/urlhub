<?php

namespace App\Http\Livewire;

use App\Rules\StrAlphaUnderscore;
use Livewire\Component;

class UrlCheck extends Component
{
    /**
     * @var string
     */
    public $keyword;

    /**
     * https://github.com/livewire/livewire/blob/6aaa3ec856/src/ComponentConcerns/ValidatesInput.php#L80
     *
     * @return array
     */
    public function rules()
    {
        return [
            'keyword' => [
                'alpha_num', 'min:2', 'max:20', 'unique:App\Models\Url', 'lowercase:field', new StrAlphaUnderscore,
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
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('livewire.url-check');
    }
}
