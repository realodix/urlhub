<?php

namespace App\Services;

class TimezonelistService
{
    const HTML_WHITESPACE = '&#160;';
    const GENERAL_TIMEZONE = 'UTC';

    protected array $continents = [
        'Africa'     => \DateTimeZone::AFRICA,
        'America'    => \DateTimeZone::AMERICA,
        'Antarctica' => \DateTimeZone::ANTARCTICA,
        'Arctic'     => \DateTimeZone::ARCTIC,
        'Asia'       => \DateTimeZone::ASIA,
        'Atlantic'   => \DateTimeZone::ATLANTIC,
        'Australia'  => \DateTimeZone::AUSTRALIA,
        'Europe'     => \DateTimeZone::EUROPE,
        'Indian'     => \DateTimeZone::INDIAN,
        'Pacific'    => \DateTimeZone::PACIFIC,
    ];

    /**
     * Create a select box of timezones
     *
     * @param string $name The name of the select tag
     * @param string|null $selected HTML <option> selected attribute
     * @param array|null $attrs HTML attribute of the <select> tag
     */
    public function toSelectBox(string $name, ?string $selected = null, ?array $attrs = null): string
    {
        $attributes = '';
        if (!empty($attrs)) {
            foreach ($attrs as $attr_name => $attr_value) {
                $attributes .= ' '.$attr_name.'="'.$attr_value.'"';
            }
        }

        $output = '<select name="'.$name.'"'.$attributes.'>';

        // General
        $output .= '<optgroup label="General">';
        $output .= $this->makeOptionTag(
            $this->formatTimezone(self::GENERAL_TIMEZONE),
            self::GENERAL_TIMEZONE,
            ($selected == self::GENERAL_TIMEZONE),
        );
        $output .= '</optgroup>';

        // Continent
        foreach ($this->continents as $continent => $mask) {
            $timezones = \DateTimeZone::listIdentifiers($mask);

            $output .= '<optgroup label="'.$continent.'">';
            foreach ($timezones as $timezone) {
                $output .= $this->makeOptionTag(
                    $this->formatTimezone($timezone, $continent),
                    $timezone,
                    ($selected == $timezone),
                );
            }
            $output .= '</optgroup>';
        }

        $output .= '</select>';

        return $output;
    }

    /**
     * Generate HTML <option> tag
     */
    protected function makeOptionTag(string $display, string $value, bool $selected): string
    {
        $attrs = $selected ? ' selected="selected"' : '';

        return '<option value="'.(string) $value.'"'.$attrs.'>'.(string) $display.'</option>';
    }

    /**
     * Format to display timezones
     */
    protected function formatTimezone(string $timezone, ?string $cutOffContinent = null): string
    {
        $displayedTz = empty($cutOffContinent) ? $timezone : substr($timezone, strlen($cutOffContinent) + 1);
        $normalizedTz = str_replace(['St_', '/', '_'], ['St. ', ' / ', ' '], $displayedTz);
        $offset = (new \DateTime('', new \DateTimeZone($timezone)))
            ->format('P');
        $separator = str_repeat(self::HTML_WHITESPACE, 3);

        return '('.self::GENERAL_TIMEZONE.$offset.')'.$separator.$normalizedTz;
    }
}
