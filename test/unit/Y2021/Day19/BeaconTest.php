<?php
declare(strict_types=1);


namespace marvellanius\Advent\Y2021\Day19;

use PHPUnit\Framework\TestCase;

final class BeaconTest extends TestCase
{
    /** @dataProvider rotationDataProvider */
    public function test_it_should_rotate_beacon(string $axis, bool $clockwise, array $coordinates, array $expected): void
    {
        // Given a beacon with coordinates
        $beacon = Beacon::createFromCoordinates(...$coordinates);

        // When asked to rotate (counter)clockwise along a given axis
        $beacon->rotate($axis, $clockwise);

        // Then I expect the coordinates to be correctly rotated
        self::assertSame($expected, $beacon->toArray());
    }

    public function rotationDataProvider(): array
    {
        return [
            ['x', true, [300, 200, 100], [300, -100, 200]],
            ['x', false, [300, 200, 100], [300, 100, -200]],
            ['y', true, [300, 200, 100], [100, 200, -300]],
            ['y', false, [300, 200, 100], [-100, 200, 300]],
            ['z', true, [300, 200, 100], [-200, 300, 100]],
            ['z', false, [300, 200, 100], [200, -300, 100]],
        ];
    }

    public function test_it_should_correctly_rotate_multiple_times(array $rotations, array $coordinates, array $expected): void
    {
        // Given a beacon with coordinates
        $beacon = Beacon::createFromCoordinates(...$coordinates);

        // When asked to rotate multiple times
        $beacon->rotateMulti($rotations);

        // Then I expect the coordinates to be correctly rotated
        self::assertSame($expected, $beacon->toArray());
    }

    public function rotationMultiDataProvider(): array
    {
        return [
            [
                [
                    ['x', true],
                    ['y', true],
                    ['z', true],
                ],
                [300, 200, 100],
                [100, 200, -300]
            ],
            ['x', false, [300, 200, 100], [300, 100, -200]],
            ['y', true, [300, 200, 100], [100, 200, -300]],
            ['y', false, [300, 200, 100], [-100, 200, 300]],
            ['z', true, [300, 200, 100], [-200, 300, 100]],
            ['z', false, [300, 200, 100], [200, -300, 100]],
        ];
    }
}