<?php

namespace App\Helpers\General;

use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Support\HtmlString;

/**
 * Class HtmlHelper.
 *
 * @codeCoverageIgnore
 */
class HtmlHelper
{
    /**
     * The URL generator instance.
     *
     * @var \Illuminate\Contracts\Routing\UrlGenerator
     */
    protected $url;

    /**
     * HtmlHelper constructor.
     *
     * @param UrlGenerator $url
     */
    public function __construct(UrlGenerator $url = null)
    {
        $this->url = $url;
    }

    /**
     * @param null $secure
     *
     * @return mixed
     */
    public function style(string $url, array $attributes = [], $secure = null): HtmlString
    {
        $defaults = [
            'media' => 'all',
            'type'  => 'text/css',
            'rel'   => 'stylesheet',
        ];

        $attributes += $defaults;

        $attributes['href'] = $this->url->asset($url, $secure);

        return $this->toHtmlString('<link'.$this->attributes($attributes).'>'.PHP_EOL);
    }

    /**
     * Generate a link to a JavaScript file.
     */
    public function script(string $url, array $attributes = [], bool $secure = null): HtmlString
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
     *
     * @return \Illuminate\Support\HtmlString
     */
    protected function toHtmlString(string $html)
    {
        return new HtmlString($html);
    }
}
