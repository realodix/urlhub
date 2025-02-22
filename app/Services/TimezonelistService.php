<?php

namespace App\Services;

class TimezonelistService
{
    const HTML_MINUS = '&#8722;';
    const HTML_PLUS = '&#43;';
    const HTML_WHITESPACE = '&#160;';

    /**
     * General timezones.
     *
     * @var array
     */
    protected $generalTimezones = [
        'GMT',
        'UTC',
    ];

    /**
     * All continents of the world.
     *
     * @var array
     */
    protected $continents = [
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
     * The filter of the groups to get.
     *
     * @var array
     */
    protected $groupsFilter = [];

    /**
     * Status of grouping the return list.
     *
     * @var bool
     */
    protected $splitGroup = true;

    /**
     * Status of showing timezone offset.
     *
     * @var bool
     */
    protected $showOffset = true;

    /**
     * The offset prefix in list.
     *
     * @var string
     */
    protected $offsetPrefix = 'GMT/UTC';

    /**
     * Set the filter of the groups want to get.
     *
     * @param array $groups
     * @return $this
     */
    public function onlyGroups($groups = [])
    {
        $this->groupsFilter = $groups;

        return $this;
    }

    /**
     * Set the filter of the groups do not want to get.
     *
     * @param array $groups
     * @return $this
     */
    public function excludeGroups($groups = [])
    {
        if (empty($groups)) {
            $this->groupsFilter = [];

            return $this;
        }

        $this->groupsFilter = array_values(array_diff(array_keys($this->continents), $groups));

        if (!in_array('General', $groups)) {
            $this->groupsFilter[] = 'General';
        }

        return $this;
    }

    /**
     * Decide whether to split group or not.
     *
     * @param bool $status
     * @return $this
     */
    public function splitGroup($status = true)
    {
        $this->splitGroup = (bool) $status;

        return $this;
    }

    /**
     * Decide whether to show the offset or not.
     *
     * @param bool $status
     * @return $this
     */
    public function showOffset($status = true)
    {
        $this->showOffset = (bool) $status;

        return $this;
    }

    /**
     * Return new static to reset all config.
     *
     * @return $this
     */
    public function reset()
    {
        return new static;
    }

    /**
     * Create an array of timezones.
     *
     * @param bool $htmlencode Use HTML entities for items
     * @return mixed
     */
    public function toArray($htmlencode = true)
    {
        $list = [];

        // If do not split group
        if (!$this->splitGroup) {
            if ($this->includeGeneral()) {
                foreach ($this->generalTimezones as $timezone) {
                    $list[$timezone] = $this->formatTimezone($timezone, null, $htmlencode);
                }
            }

            foreach ($this->loadContinents() as $continent => $mask) {
                $timezones = \DateTimeZone::listIdentifiers($mask);

                foreach ($timezones as $timezone) {
                    $list[$timezone] = $this->formatTimezone($timezone, null, $htmlencode);
                }
            }

            return $list;
        }

        // If split group
        if ($this->includeGeneral()) {
            foreach ($this->generalTimezones as $timezone) {
                $list['General'][$timezone] = $this->formatTimezone($timezone, null, $htmlencode);
            }
        }

        foreach ($this->loadContinents() as $continent => $mask) {
            $timezones = \DateTimeZone::listIdentifiers($mask);

            foreach ($timezones as $timezone) {
                $list[$continent][$timezone] = $this->formatTimezone($timezone, $continent, $htmlencode);
            }
        }

        return $list;
    }

    /**
     * Alias of the `toSelectBox()` method.
     *
     * @deprecated 6.0.0 This method name no longer matches the semantics
     *
     * @param string $name The name of the select tag
     * @param string|null $selected The selected value
     * @param array|string|null $attrs The HTML attributes of select tag
     * @param bool $htmlencode Use HTML entities for values of select tag
     * @return string
     */
    public function create($name, $selected = null, $attrs = null, $htmlencode = true)
    {
        return $this->toSelectBox($name, $selected, $attrs, $htmlencode);
    }

    /**
     * Create a select box of timezones.
     *
     * @param string $name The name of the select tag
     * @param string|null $selected The selected value
     * @param array|string|null $attrs The HTML attributes of select tag
     * @param bool $htmlencode Use HTML entities for values of select tag
     * @return string
     */
    public function toSelectBox($name, $selected = null, $attrs = null, $htmlencode = true)
    {
        // Attributes for select element
        $attrString = null;

        if (!empty($attrs)) {
            if (is_array($attrs)) {
                foreach ($attrs as $attr_name => $attr_value) {
                    $attrString .= ' '.$attr_name.'="'.$attr_value.'"';
                }
            } else {
                $attrString = $attrs;
            }
        }

        if ($this->splitGroup) {
            return $this->makeSelectTagWithGroup($name, $selected, $attrString, $htmlencode);
        }

        return $this->makeSelectTagWithoutGroup($name, $selected, $attrString, $htmlencode);
    }

    /**
     * Generate select element with the optgroup tag.
     *
     * @param string $name The name of the select tag
     * @param string|null $selected The selected value
     * @param string|null $attrs The HTML attributes of select tag
     * @param bool $htmlencode Use HTML entities for values of select tag
     * @return string
     */
    protected function makeSelectTagWithGroup($name, $selected = null, $attrs = null, $htmlencode = true)
    {
        $attrs = !empty($attrs) ? ' '.trim((string) $attrs) : '';
        $output = '<select name="'.(string) $name.'"'.$attrs.'>';

        if ($this->includeGeneral()) {
            $output .= '<optgroup label="General">';

            foreach ($this->generalTimezones as $timezone) {
                $output .= $this->makeOptionTag($this->formatTimezone($timezone, null, $htmlencode), $timezone, ($selected == $timezone));
            }

            $output .= '</optgroup>';
        }

        foreach ($this->loadContinents() as $continent => $mask) {
            $timezones = \DateTimeZone::listIdentifiers($mask);
            $output .= '<optgroup label="'.$continent.'">';

            foreach ($timezones as $timezone) {
                $output .= $this->makeOptionTag($this->formatTimezone($timezone, $continent, $htmlencode), $timezone, ($selected == $timezone));
            }

            $output .= '</optgroup>';
        }

        $output .= '</select>';

        return $output;
    }

    /**
     * Generate select element without the optgroup tag.
     *
     * @param string $name The name of the select tag
     * @param string|null $selected The selected value
     * @param string|null $attrs The HTML attributes of select tag
     * @param bool $htmlencode Use HTML entities for values of select tag
     * @return string
     */
    protected function makeSelectTagWithoutGroup($name, $selected = null, $attrs = null, $htmlencode = true)
    {
        $attrs = !empty($attrs) ? ' '.trim((string) $attrs) : '';
        $output = '<select name="'.(string) $name.'"'.$attrs.'>';

        if ($this->includeGeneral()) {
            foreach ($this->generalTimezones as $timezone) {
                $output .= $this->makeOptionTag($this->formatTimezone($timezone, null, $htmlencode), $timezone, ($selected == $timezone));
            }
        }

        foreach ($this->loadContinents() as $continent => $mask) {
            $timezones = \DateTimeZone::listIdentifiers($mask);

            foreach ($timezones as $timezone) {
                $output .= $this->makeOptionTag($this->formatTimezone($timezone, null, $htmlencode), $timezone, ($selected == $timezone));
            }
        }

        $output .= '</select>';

        return $output;
    }

    /**
     * Generate the option HTML tag.
     *
     * @param string $display
     * @param string $value
     * @param bool $selected
     * @return string
     */
    protected function makeOptionTag($display, $value, $selected = false)
    {
        $attrs = (bool) $selected ? ' selected="selected"' : '';

        return '<option value="'.(string) $value.'"'.$attrs.'>'.(string) $display.'</option>';
    }

    /**
     * DetermineCheck if the general timezones is loaded in the returned result.
     *
     * @return bool
     */
    protected function includeGeneral()
    {
        return empty($this->groupsFilter) || in_array('General', $this->groupsFilter);
    }

    /**
     * Load filtered continents.
     *
     * @return array
     */
    protected function loadContinents()
    {
        if (empty($this->groupsFilter)) {
            return $this->continents;
        }

        return array_filter($this->continents, function ($key) {
            return in_array($key, $this->groupsFilter);
        }, ARRAY_FILTER_USE_KEY);
    }

    /**
     * Format to display timezones.
     *
     * @param string $timezone
     * @param string|null $cutOffContinent
     * @param bool $htmlencode
     * @return string
     */
    protected function formatTimezone($timezone, $cutOffContinent = null, $htmlencode = true)
    {
        $displayedTimezone = empty($cutOffContinent) ? $timezone : substr($timezone, strlen($cutOffContinent) + 1);
        $normalizedTimezone = $this->normalizeTimezone($displayedTimezone, $htmlencode);

        if (!$this->showOffset) {
            return $normalizedTimezone;
        }

        $notmalizedOffset = $this->normalizeOffset($this->getOffset($timezone), $htmlencode);
        $separator = $this->normalizeSeparator($htmlencode);

        return '('.$this->offsetPrefix.$notmalizedOffset.')'.$separator.$normalizedTimezone;
    }

    /**
     * Normalize the offset.
     *
     * @param string $offset
     * @param bool $htmlencode
     * @return string
     */
    protected function normalizeOffset($offset, $htmlencode = true)
    {
        $search = ['-', '+'];
        $replace = $htmlencode ? [' '.self::HTML_MINUS.' ', ' '.self::HTML_PLUS.' '] : [' - ', ' + '];

        return str_replace($search, $replace, $offset);
    }

    /**
     * Normalize the timezone.
     *
     * @param string $timezone
     * @param bool $htmlencode
     * @return string
     */
    protected function normalizeTimezone($timezone, $htmlencode = true)
    {
        $search = ['St_', '/', '_'];
        $replace = ['St. ', ' / ', ' '];

        return str_replace($search, $replace, $timezone);
    }

    /**
     * Normalize the separator beetween the timezone and offset.
     *
     * @param bool $htmlencode
     * @return string
     */
    protected function normalizeSeparator($htmlencode = true)
    {
        return $htmlencode ? str_repeat(self::HTML_WHITESPACE, 5) : ' ';
    }

    /**
     * Get the timezone offset.
     *
     * @param string $timezone
     * @return string
     */
    protected function getOffset($timezone)
    {
        $time = new \DateTime('', new \DateTimeZone($timezone));

        return $time->format('P');
    }

    /**
     * Get the difference of timezone to Coordinated Universal Time (UTC).
     *
     * @param string $timezone
     * @return string
     */
    protected function getUTCOffset($timezone)
    {
        $dateTimeZone = new \DateTimeZone($timezone);
        $utcTime = new \DateTime('', new \DateTimeZone('UTC'));
        $offset = $dateTimeZone->getOffset($utcTime);
        $format = gmdate('H:i', abs($offset));

        return $offset >= 0 ? "+{$format}" : "-{$format}";
    }
}
