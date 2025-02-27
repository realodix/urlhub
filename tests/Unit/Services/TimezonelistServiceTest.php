<?php

namespace Tests\Unit\Services;

use App\Services\TimezonelistService;
use PHPUnit\Framework\Attributes as PHPUnit;
use PHPUnit\Framework\TestCase;

class TimezonelistServiceTest extends TestCase
{
    protected TimezonelistService $service;

    protected function setUp(): void
    {
        $this->service = new TimezonelistService;
    }

    #[PHPUnit\Test]
    public function toSelectBox(): void
    {
        $output = $this->service->toSelectBox('timezone_default');
        $this->assertStringStartsWith('<select name="timezone_default"', $output);
        $this->assertStringContainsString('<optgroup label="General">', $output);
        $this->assertStringContainsString('<option value="UTC">(UTC+00:00)&#160;&#160;&#160;UTC</option>', $output);
        $this->assertStringContainsString('<optgroup label="Africa">', $output);
        $this->assertStringContainsString('<option value="Africa/Abidjan">(UTC', $output);
        $this->assertStringContainsString('<optgroup label="America">', $output);
        $this->assertStringContainsString('<option value="America/New_York">(UTC-05:00)', $output);
        $this->assertStringContainsString('<optgroup label="Antarctica">', $output);
        $this->assertStringContainsString('<option value="Antarctica/Casey">(UTC', $output);
        $this->assertStringContainsString('<optgroup label="Arctic">', $output);
        $this->assertStringContainsString('<option value="Arctic/Longyearbyen">(UTC', $output);
        $this->assertStringContainsString('<optgroup label="Asia">', $output);
        $this->assertStringContainsString('<option value="Asia/Aden">(UTC', $output);
        $this->assertStringContainsString('<optgroup label="Atlantic">', $output);
        $this->assertStringContainsString('<option value="Atlantic/Azores">(UTC', $output);
        $this->assertStringContainsString('<optgroup label="Australia">', $output);
        $this->assertStringContainsString('<option value="Australia/Adelaide">(UTC', $output);
        $this->assertStringContainsString('<optgroup label="Europe">', $output);
        $this->assertStringContainsString('<option value="Europe/Amsterdam">(UTC', $output);
        $this->assertStringContainsString('<optgroup label="Indian">', $output);
        $this->assertStringContainsString('<option value="Indian/Antananarivo">(UTC', $output);
        $this->assertStringContainsString('<optgroup label="Pacific">', $output);
        $this->assertStringContainsString('<option value="Pacific/Apia">(UTC+13:00)', $output);
        $this->assertStringEndsWith('</select>', $output);

        // Implicitly tests normalizeSeparator via output
        $this->assertStringContainsString('&#160;&#160;&#160;', $output, 'Ensure normalizeSeparator works');
    }

    public function testToSelectBox_WithSelectedValue(): void
    {
        $selectedTimezone = 'America/New_York';
        $output = $this->service->toSelectBox('timezone_selected', $selectedTimezone);
        $this->assertStringContainsString('<option value="America/New_York" selected>', $output);
    }

    public function testToSelectBox_WithAttributes(): void
    {
        $attrsArray = ['class' => 'form-control', 'id' => 'timezone-select'];
        $outputArray = $this->service->toSelectBox('timezone_attrs_array', null, $attrsArray);
        $this->assertStringContainsString('<select name="timezone_attrs_array" class="form-control" id="timezone-select">', $outputArray);
    }

    public function testNormalizeTimezone(): void
    {
        $output = $this->service->toSelectBox('timezone_default');
        $this->assertStringContainsString(
            '<option value="America/Argentina/Rio_Gallegos">(UTC-03:00)&#160;&#160;&#160;Argentina / Rio Gallegos</option>',
            $output,
        );
        $this->assertStringContainsString(
            '<option value="America/St_Johns">(UTC-03:30)&#160;&#160;&#160;St. Johns</option>',
            $output,
        );
    }

    public function testConstants(): void
    {
        $this->assertSame('&#160;', TimezonelistService::HTML_WHITESPACE);
    }
}
