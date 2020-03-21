<?php

declare(strict_types=1);

namespace Sihae\Tests\Unit\Utils;

use PHPUnit\Framework\TestCase;
use Sihae\Utils\Safe;
use stdClass;

final class SafeTest extends TestCase
{
    /**
     * @param string $key
     * @param array<string, string>|object|null $maybeArray
     * @param string $default
     * @param string $expected
     *
     * @dataProvider getStringProvider
     */
    public function testItSafelyGetsAStringromTheGivenParameter(
        string $key,
        $maybeArray,
        string $default,
        string $expected
    ): void {
        $actual = Safe::getString($key, $maybeArray, $default);

        $this->assertSame($expected, $actual);
    }

    /**
     * @param string $key
     * @param array<string, array<mixed>>|object|null $maybeArray
     * @param array<mixed> $default
     * @param array<mixed> $expected
     *
     * @dataProvider getArrayProvider
     */
    public function testItSafelyGetsAnArrayromTheGivenParameter(
        string $key,
        $maybeArray,
        array $default,
        array $expected
    ): void {
        $actual = Safe::getArray($key, $maybeArray, $default);

        $this->assertSame($expected, $actual);
    }

    /**
     * @return array<string, array>
     */
    public function getStringProvider(): array
    {
        return [
            'null returns default (empty string)' => [
                'hello',
                null,
                '',
                '',
            ],
            'empty array returns default (empty string)' => [
                'hello',
                [],
                '',
                '',
            ],
            'object returns default (empty string)' => [
                'hello',
                new stdClass(),
                '',
                '',
            ],
            'function returns default (empty string)' => [
                'hello',
                static function (): void {
                },
                '',
                '',
            ],
            'int returns default (empty string)' => [
                'hello',
                0,
                '',
                '',
            ],
            'null returns default (arbitrary string)' => [
                'hello',
                null,
                'arbitrary',
                'arbitrary',
            ],
            'empty array returns default (arbitrary string)' => [
                'hello',
                [],
                'arbitrary',
                'arbitrary',
            ],
            'object returns default (arbitrary string)' => [
                'hello',
                new stdClass(),
                'arbitrary',
                'arbitrary',
            ],
            'function returns default (arbitrary string)' => [
                'hello',
                static function (): void {
                },
                'arbitrary',
                'arbitrary',
            ],
            'int returns default (arbitrary string)' => [
                'hello',
                0,
                'arbitrary',
                'arbitrary',
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

    /**
     * @return array<string, array>
     */
    public function getArrayProvider(): array
    {
        return [
            'null returns default (empty array)' => [
                'hello',
                null,
                [],
                [],
            ],
            'empty array returns default (empty array)' => [
                'hello',
                [],
                [],
                [],
            ],
            'object returns default (empty array)' => [
                'hello',
                new stdClass(),
                [],
                [],
            ],
            'function returns default (empty array)' => [
                'hello',
                static function (): void {
                },
                [],
                [],
            ],
            'int returns default (empty array)' => [
                'hello',
                0,
                [],
                [],
            ],
            'null returns default (arbitrary array)' => [
                'hello',
                null,
                ['a', 'r', 'b', 'i', 't', 'r', 'a', 'r', 'y'],
                ['a', 'r', 'b', 'i', 't', 'r', 'a', 'r', 'y'],
            ],
            'empty array returns default (arbitrary array)' => [
                'hello',
                [],
                ['a', 'r', 'b', 'i', 't', 'r', 'a', 'r', 'y'],
                ['a', 'r', 'b', 'i', 't', 'r', 'a', 'r', 'y'],
            ],
            'object returns default (arbitrary array)' => [
                'hello',
                new stdClass(),
                ['a', 'r', 'b', 'i', 't', 'r', 'a', 'r', 'y'],
                ['a', 'r', 'b', 'i', 't', 'r', 'a', 'r', 'y'],
            ],
            'function returns default (arbitrary array)' => [
                'hello',
                static function (): void {
                },
                ['a', 'r', 'b', 'i', 't', 'r', 'a', 'r', 'y'],
                ['a', 'r', 'b', 'i', 't', 'r', 'a', 'r', 'y'],
            ],
            'int returns default (arbitrary array)' => [
                'hello',
                0,
                ['a', 'r', 'b', 'i', 't', 'r', 'a', 'r', 'y'],
                ['a', 'r', 'b', 'i', 't', 'r', 'a', 'r', 'y'],
            ],
            'returns the value of the given key' => [
                'hello',
                ['hello' => ['w', 'o', 'r', 'l', 'd']],
                [],
                ['w', 'o', 'r', 'l', 'd'],
            ],
            'returns the value of the given key when other keys exist' => [
                'hi',
                ['hello' => ['world'], 'hi' => ['planet'], 'hey' => ['oblate spheroid']],
                [],
                ['planet'],
            ],
        ];
    }
}
