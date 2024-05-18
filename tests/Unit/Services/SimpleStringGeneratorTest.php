<?php

namespace Tests\Unit\Services;

use App\Services\KeyGeneratorService;
use PHPUnit\Framework\Attributes as PHPUnit;
use Tests\TestCase;

#[PHPUnit\Group('services')]
#[PHPUnit\Group('key-generator')]
class SimpleStringGeneratorTest extends TestCase
{
    private KeyGeneratorService $generator;

    protected function setUp(): void
    {
        parent::setUp();

        $this->generator = app(KeyGeneratorService::class);
    }

    public static function generateSimpleStringProvider(): array
    {
        return [
            ['glvel', 'https://github.com/laravel/laravel'],
            ['sqvel', 'https://stackoverflow.com/questions/tagged/laravel'],
            ['yhvel', 'https://www.youtube.com/hashtag/laravel'],
            ['rrvel', 'https://www.reddit.com/r/laravel/'],
            ['dtvel', 'https://dev.to/t/laravel'],
            ['ltvel', 'https://laracasts.com/topics/laravel'],
            ['lfvel', 'https://laravel.io/forum/tags/laravel'],
            ['mtvel', 'https://medium.com/tag/laravel'],
            ['fivel', 'https://fontawesome.com/icons/laravel'],

            ['ewvel', 'https://en.wikipedia.org/wiki/Laravel'],
            ['iwvel', 'https://id.wikipedia.org/wiki/Laravel'],

            ['gtork', 'https://github.com/topics/framework'],
            ['glork', 'https://github.com/laravel/framework'],
            ['gcork', 'https://github.com/codeigniter4/framework'],
            ['gsork', 'https://github.com/spring-projects/spring-framework'],
            ['giork', 'https://github.com/ionic-team/ionic-framework'],

            // Path + query
            ['pc991', 'https://www.phpbb.com/community/viewtopic.php?f=14&t=2646991'],
            ['pc426', 'https://www.phpbb.com/community/viewtopic.php?f=14&t=2650426'],

            // Path + fragment
            ['gdnit', 'https://getcomposer.org/doc/03-cli.md#init'],
            ['gdump', 'https://getcomposer.org/doc/03-cli.md#bump'],
        ];
    }

    #[PHPUnit\DataProvider('generateSimpleStringProvider')]
    public function testGenerateSimpleStringMethod($expected, $actual): void
    {
        config(['urlhub.keyword_length' => 5]);

        $this->assertSame($expected, $this->generator->simpleString($actual));
    }

    public function testOutputMustBeAlphabetical(): void
    {
        config(['urlhub.keyword_length' => 6]);

        $this->assertSame(
            'eacdef',
            $this->generator->simpleString('http://example.com/a-b-c-d-e-f')
        );

        $this->assertSame(
            'eacdef',
            $this->generator->simpleString('http://example.com/a/b/c/d/e/f')
        );
    }

    public function testOutputMustBeLowercase(): void
    {
        config(['urlhub.keyword_length' => 6]);
        $actual = 'https://example.com/ABCDEF';

        $this->assertSame(
            'eacdef',
            $this->generator->simpleString($actual),
        );
    }

    /**
     * The generated string length should be as expected
     */
    public function testStringLength(): void
    {
        $length = 4;
        config(['urlhub.keyword_length' => $length]);
        $this->assertSame(
            $length,
            strlen($this->generator->simpleString('https://github.com/realodix'))
        );

        $length = 6;
        config(['urlhub.keyword_length' => $length]);
        $this->assertSame(
            $length,
            strlen($this->generator->simpleString('https://github.com/realodix'))
        );
    }

    /**
     * Testing for problems caused by the url path. It usually occurs when the path
     * string length does not match the criteria.
     */
    public function testPathProblem(): void
    {
        // Tidak ada path
        config(['urlhub.keyword_length' => 5]);
        $this->assertSame('ubcom', $this->generator->simpleString('https://github.com/'));
        $this->assertSame('ubcom', $this->generator->simpleString('https://github.com'));

        // Panjang path tidak sesuai kriteria
        config(['urlhub.keyword_length' => 4]);
        $this->assertSame('eupe', $this->generator->simpleString('http://example.com/U?p=e'));
        config(['urlhub.keyword_length' => 7]);
        $this->assertSame('comdocs', $this->generator->simpleString('https://laravel.com/docs'));
    }
}
