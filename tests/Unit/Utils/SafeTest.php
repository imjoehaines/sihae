<?php declare(strict_types=1);

namespace Sihae\Tests\Unit\Utils;

use stdClass;
use Sihae\Utils\Safe;
use PHPUnit\Framework\TestCase;

final class SafeTest extends TestCase
{
    /**
     * @template T
     * @param string $key
     * @param array<string, T>|object|null $maybeArray
     * @param T $default
     * @param T $expected
     *
     * @dataProvider getProvider
     */
    public function testItSafelyGetsAnElementFromTheGivenParameter(
        string $key,
        $maybeArray,
        $default,
        $expected
    ): void {
        $actual = Safe::get($key, $maybeArray, $default);

        $this->assertSame($expected, $actual);
    }

    /**
     * @return array<string, array>
     */
    public function getProvider(): array
    {
        return [
            'null returns default' => [
                'hello',
                null,
                '',
                '',
            ],
            'empty array returns default' => [
                'hello',
                [],
                '',
                '',
            ],
            'object returns default' => [
                'hello',
                new stdClass(),
                '',
                '',
            ],
            'function returns default' => [
                'hello',
                fn($a) => $a,
                '',
                '',
            ],
            'int returns default' => [
                'hello',
                0,
                '',
                '',
            ],
            'default can be a string' => [
                'hello',
                [],
                'world',
                'world',
            ],
            'default can be an int' => [
                'hello',
                [],
                1298,
                1298,
            ],
            'default can be an array' => [
                'hello',
                [],
                [1, 2, 9, 8],
                [1, 2, 9, 8],
            ],
            'returns the value of the given key' => [
                'hello',
                ['hello' => 'world'],
                '',
                'world',
            ],
            'returns the value of the given key when other keys exist' => [
                'hi',
                ['hello' => 'world', 'hi' => 'planet', 'hey' => 'oblate spheroid'],
                '',
                'planet',
            ],
        ];
    }
}
