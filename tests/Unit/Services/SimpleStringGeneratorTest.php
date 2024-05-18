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
            ['sqdel', 'https://stackoverflow.com/questions/tagged/laravel'],
            ['yhvel', 'https://www.youtube.com/hashtag/laravel'],
            ['rrvel', 'https://www.reddit.com/r/laravel/'],
            ['dtvel', 'https://dev.to/t/laravel'],
            ['ltvel', 'https://laracasts.com/topics/laravel'],
            ['lfsel', 'https://laravel.io/forum/tags/laravel'],
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
            'eabdef',
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

    public function testPathWithUrlEncode(): void
    {
        config(['urlhub.keyword_length' => 5]);
        $url = 'https://www.google.com/url?sa=t&url=https://laravel.com/';

        $this->assertSame('gucom', $this->generator->simpleString($url));
    }

    public function testOfUniqueness(): void
    {
        $data = [
            'https://github.com/laravel/laravel',
            'https://stackoverflow.com/questions/tagged/laravel',
            'https://www.youtube.com/hashtag/laravel',
            'https://www.reddit.com/r/laravel/',
            'https://dev.to/t/laravel',
            'https://laracasts.com/topics/laravel',
            'https://laravel.io/forum/tags/laravel',
            'https://medium.com/tag/laravel',
            'https://fontawesome.com/icons/laravel',

            'https://en.wikipedia.org/wiki/Laravel',
            'https://id.wikipedia.org/wiki/Laravel',

            'https://github.com/topics/framework',
            'https://github.com/laravel/framework',
            'https://github.com/codeigniter4/framework',
            'https://github.com/spring-projects/spring-framework',
            'https://github.com/ionic-team/ionic-framework',

            // Path + query
            'https://www.phpbb.com/community/viewtopic.php?f=14&t=2646991',
            'https://www.phpbb.com/community/viewtopic.php?f=14&t=2650426',

            // Path + fragment
            'https://getcomposer.org/doc/03-cli.md#init',
            'https://getcomposer.org/doc/03-cli.md#bump',

            // Path is almost exactly the same
            'http://example.com/a-b-c-d-e-f',
            'http://example.com/a/b/c/d/e/f',
            'https://github.com/laravel/laravel/issues',
            'https://github.com/laravel/framework/issues',
            'https://github.com/laravel-zero/laravel-zero/issues',
            'https://github.com/larastan/larastan/issues',
            'https://github.com/barryvdh/laravel-debugbar/issues',
            'https://github.com/spatie/laravel-permission/issues',
            'https://github.com/SpartnerNL/Laravel-Excel/issues',
            'https://github.com/alexeymezenin/laravel-best-practices/issues',
            'https://github.com/mongodb/laravel-mongodb/issues',
            'https://github.com/php/php-src/issues',
            'https://github.com/phpmyadmin/phpmyadmin/issues',
        ];

        $collection = collect($data)
            ->map(fn ($item) => $this->generator->simpleString($item))
            ->unique()
            ->count();

        $this->assertSame(count($data), $collection);
    }
}
