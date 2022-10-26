<?php

namespace App\Helpers\General;

use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Support\HtmlString;

/**
 * Class HtmlHelper.
 */
class HtmlHelper
{
    /**
     * The URL generator instance.
     */
    protected UrlGenerator $url;

    /**
     * HtmlHelper constructor.
     */
    public function __construct(UrlGenerator $url)
    {
        $this->url = $url;
    }

    /**
     * @return \Illuminate\Support\HtmlString
     */
    public function style(string $url, array $attributes = [], bool $secure = null)
    {
        $attributes += [
            'media' => 'all',
            'type'  => 'text/css',
            'rel'   => 'stylesheet',
        ];

        $attributes['href'] = $this->url->asset($url, $secure);

        return $this->toHtmlString('<link'.$this->attributes($attributes).'>'.PHP_EOL);
    }

    /**
     * Generate a link to a JavaScript file.
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function script(string $url, array $attributes = [], bool $secure = null)
    {
        $attributes['src'] = $this->url->asset($url, $secure);

        return $this->toHtmlString('<script'.$this->attributes($attributes).'></script>'.PHP_EOL);
    }

    /**
     * Build an HTML attribute string from an array.
     */
    public function attributes(array $attributes): string
    {
        $html = [];

        foreach ($attributes as $key => $value) {
            $element = $this->attributeElement($key, $value);

            $html[] = $element;
        }

        return count($html) > 0 ? ' '.implode(' ', $html) : '';
    }

    /**
     * Build a single attribute element.
     */
    protected function attributeElement(string $key, string $value): string
    {
        return $key.'="'.e($value).'"';
    }

    /**
     * Transform the string to an Html serializable object.
     */
    protected function toHtmlString(string $html): HtmlString
    {
        return new HtmlString($html);
    }
}
