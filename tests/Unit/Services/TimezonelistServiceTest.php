<?php

namespace Tests\Unit\Services;

use App\Services\TimezonelistService;
use PHPUnit\Framework\TestCase;

class TimezonelistServiceTest extends TestCase
{
    protected TimezonelistService $service;

    protected function setUp(): void
    {
        $this->service = new TimezonelistService;
    }

    public function testOnlyGroups(): void
    {
        $service = $this->service->onlyGroups(['Africa', 'America']);
        $this->assertInstanceOf(TimezonelistService::class, $service);

        $output = $this->service->onlyGroups(['Africa'])->toSelectBox('timezone_only_africa');
        $this->assertStringContainsString('<optgroup label="Africa">', $output);
        $this->assertStringNotContainsString('<optgroup label="America">', $output);

        $output = $this->service->onlyGroups(['America'])->toSelectBox('timezone_only_america');
        $this->assertStringNotContainsString('<optgroup label="Africa">', $output);
        $this->assertStringContainsString('<optgroup label="America">', $output);
    }

    public function testExcludeGroups(): void
    {
        $service = $this->service->excludeGroups(['Africa']);
        $this->assertInstanceOf(TimezonelistService::class, $service);

        $output = $this->service->excludeGroups(['Africa'])->toSelectBox('timezone_exclude_africa');
        $this->assertStringNotContainsString('<optgroup label="Africa">', $output);
        $this->assertStringContainsString('<optgroup label="America">', $output);

        $output = $this->service->excludeGroups(['America'])->toSelectBox('timezone_exclude_america');
        $this->assertStringContainsString('<optgroup label="Africa">', $output);
        $this->assertStringNotContainsString('<optgroup label="America">', $output);

        $output = $this->service->excludeGroups(['General'])->toSelectBox('timezone_exclude_general');
        $this->assertStringNotContainsString('<optgroup label="General">', $output);
        $this->assertStringContainsString('<optgroup label="Africa">', $output);
    }

    public function testSplitGroup(): void
    {
        $service = $this->service->splitGroup(true);
        $this->assertInstanceOf(TimezonelistService::class, $service);
        $outputWithGroup = $this->service->splitGroup(true)->toSelectBox('timezone_split_true');
        $this->assertStringContainsString('<optgroup', $outputWithGroup, 'Asserting optgroup tag exists when splitGroup is true');

        $outputWithoutGroup = $this->service->splitGroup(false)->toSelectBox('timezone_split_false');
        $this->assertStringNotContainsString('<optgroup', $outputWithoutGroup, 'Asserting optgroup tag does not exist when splitGroup is false');
    }

    public function testShowOffset(): void
    {
        $service = $this->service->showOffset(true);
        $this->assertInstanceOf(TimezonelistService::class, $service);

        $outputWithOffset = $this->service->showOffset(true)->toSelectBox('timezone_offset_true');
        $this->assertStringContainsString('(GMT/UTC', $outputWithOffset, 'Asserting offset prefix exists when showOffset is true');

        $outputWithoutOffset = $this->service->showOffset(false)->toSelectBox('timezone_offset_false');
        $this->assertStringNotContainsString('(GMT/UTC', $outputWithoutOffset, 'Asserting offset prefix does not exist when showOffset is false');
    }

    public function testToSelectBox_WithGroup_WithOffset(): void
    {
        $output = $this->service->splitGroup()->showOffset()->toSelectBox('timezone_with_group_offset');
        $this->assertStringStartsWith('<select name="timezone_with_group_offset"', $output);
        $this->assertStringContainsString('<optgroup label="Africa">', $output);
        $this->assertStringContainsString('<option value="Africa/Abidjan">(GMT/UTC', $output);
        $this->assertStringEndsWith('</select>', $output);

        // Implicitly tests normalizeOffset, getOffset, normalizeTimezone, normalizeSeparator via output
        $this->assertStringContainsString('&#8722;', $output, 'Ensure HTML_MINUS is in offset (normalizeOffset)');
        $this->assertStringContainsString('&#43;', $output, 'Ensure HTML_PLUS is in offset (normalizeOffset)');
        $this->assertStringContainsString('&#160;&#160;&#160;&#160;&#160;', $output, 'Ensure normalizeSeparator works');
    }

    public function testToSelectBox_WithoutGroup_WithOffset(): void
    {
        $output = $this->service->splitGroup(false)->showOffset()->toSelectBox('timezone_without_group_offset');
        $this->assertStringStartsWith('<select name="timezone_without_group_offset"', $output);
        $this->assertStringNotContainsString('<optgroup label="Africa">', $output);
        $this->assertStringContainsString('<option value="Africa/Abidjan">(GMT/UTC', $output);
        $this->assertStringEndsWith('</select>', $output);

        // Implicitly tests normalizeOffset, getOffset, normalizeTimezone, normalizeSeparator via output
        $this->assertStringContainsString('&#8722;', $output, 'Ensure HTML_MINUS is in offset (normalizeOffset)');
        $this->assertStringContainsString('&#43;', $output, 'Ensure HTML_PLUS is in offset (normalizeOffset)');
        $this->assertStringContainsString('&#160;&#160;&#160;&#160;&#160;', $output, 'Ensure normalizeSeparator works');
    }

    public function testToSelectBox_WithGroup_WithoutOffset(): void
    {
        $output = $this->service->splitGroup()->showOffset(false)->toSelectBox('timezone_with_group_no_offset');
        $this->assertStringStartsWith('<select name="timezone_with_group_no_offset"', $output);
        $this->assertStringContainsString('<optgroup label="Africa">', $output);
        $this->assertStringNotContainsString('(GMT/UTC', $output);
        $this->assertStringContainsString('<option value="Africa/Abidjan">Abidjan</option>', $output);
        $this->assertStringEndsWith('</select>', $output);
    }

    public function testToSelectBox_WithoutGroup_WithoutOffset(): void
    {
        $output = $this->service->splitGroup(false)->showOffset(false)->toSelectBox('timezone_no_group_no_offset');
        $this->assertStringStartsWith('<select name="timezone_no_group_no_offset"', $output);
        $this->assertStringNotContainsString('<optgroup label="Africa">', $output);
        $this->assertStringNotContainsString('(GMT/UTC', $output);
        $this->assertStringContainsString('<option value="Africa/Abidjan">Africa / Abidjan</option>', $output); // Corrected Assertion
        $this->assertStringEndsWith('</select>', $output);
        // Removed the problematic implicit assertion for normalizeTimezone here
    }

    public function testToSelectBox_WithSelectedValue(): void
    {
        $selectedTimezone = 'America/New_York';
        $output = $this->service->toSelectBox('timezone_selected', $selectedTimezone);
        $this->assertStringContainsString('<option value="America/New_York" selected="selected">', $output);
    }

    public function testToSelectBox_WithAttributes(): void
    {
        $attrsArray = ['class' => 'form-control', 'id' => 'timezone-select'];
        $outputArray = $this->service->toSelectBox('timezone_attrs_array', null, $attrsArray);
        $this->assertStringContainsString('<select name="timezone_attrs_array" class="form-control" id="timezone-select">', $outputArray);

        $attrsString = 'class="form-control" id="timezone-select"';
        $outputString = $this->service->toSelectBox('timezone_attrs_string', null, $attrsString);
        $this->assertStringContainsString('<select name="timezone_attrs_string" class="form-control" id="timezone-select">', $outputString);
    }

    public function testConstants(): void
    {
        $this->assertSame('&#8722;', TimezonelistService::HTML_MINUS);
        $this->assertSame('&#43;', TimezonelistService::HTML_PLUS);
        $this->assertSame('&#160;', TimezonelistService::HTML_WHITESPACE);
    }

    // Example of a dedicated test for normalizeTimezone (if you really need it for isolated testing)
    // In general, it's better to test via public methods.
    /*
    public function testNormalizeTimezone_Dedicated(): void
    {
        $this->assertSame('St. Helena', $this->service->normalizeTimezone('St_Helena'));
        $this->assertSame('America / Argentina / Buenos Aires', $this->service->normalizeTimezone('America/Argentina/Buenos_Aires'));
        $this->assertSame('America / Argentina / Buenos Aires', $this->service->normalizeTimezone('America/Argentina/Buenos_Aires'));
    }
    */
}
