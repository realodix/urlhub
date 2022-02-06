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
     * @param  UrlGenerator  $url
     */
    public function __construct(UrlGenerator $url = null)
    {
        $this->url = $url;
    }

    /**
     * @param  string  $url
     * @param  array  $attributes
     * @param  null  $secure
     *
     * @return mixed
     */
    public function style(string $url, array $attributes = [], $secure = null)
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
     *
     * @param  string  $url
     * @param  array  $attributes
     * @param  bool  $secure
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function script(string $url, array $attributes = [], $secure = null)
    {
        $attributes['src'] = $this->url->asset($url, $secure);

        return $this->toHtmlString('<script'.$this->attributes($attributes).'></script>'.PHP_EOL);
    }

    /**
     * Build an HTML attribute string from an array.
     *
     * @param  array  $attributes
     *
     * @return string
     */
    public function attributes(array $attributes)
    {
        $html = [];

        foreach ((array) $attributes as $key => $value) {
            $element = $this->attributeElement($key, $value);

            if (! is_null($element)) {
                $html[] = $element;
            }
        }

        return count($html) > 0 ? ' '.implode(' ', $html) : '';
    }

    /**
     * Build a single attribute element.
     *
     * @param  string  $key
     * @param  string  $value
     *
     * @return string
     */
    protected function attributeElement(string $key, string $value)
    {
        // For numeric keys we will assume that the value is a boolean attribute where the
        // presence of the attribute represents a true value and the absence represents a
        // false value. This will convert HTML attributes such as "required" to a correct
        // form instead of using incorrect numerics.
        if (is_numeric($key)) {
            return $value;
        }

        // Treat boolean attributes as HTML properties
        if (is_bool($value) && $key != 'value') {
            return $value ? $key : '';
        }

        if (! is_null($value)) {
            return $key.'="'.e($value).'"';
        }
    }

    /**
     * Transform the string to an Html serializable object.
     *
     * @param  string  $html
     *
     * @return \Illuminate\Support\HtmlString
     */
    protected function toHtmlString(string $html)
    {
        return new HtmlString($html);
    }
}
