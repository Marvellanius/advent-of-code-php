<?php
declare(strict_types=1);


namespace marvellanius\Advent\Y2021\Day19;

final class Beacon
{
    public static function createFromCoordinates(int $x, int $y, int $z): self
    {
        return new self($x, $y, $z);
    }

    public function __construct(
        public int $x,
        public int $y,
        public int $z
    ) {
    }

    /**
     * Rotate a beacon's readout 90 degrees clockwise (true) or counterclockwise (false) along a given axis
     */
    public function rotate(string $axis, bool $clockwise): void
    {
        switch ($axis) {
            case 'x':
                if ($clockwise) {
                    $y = -$this->z;
                    $z = $this->y;
                } else {
                    $y = $this->z;
                    $z = -$this->y;
                }
                $this->y = $y;
                $this->z = $z;
                break;
            case 'y':
                if ($clockwise) {
                    $x = $this->z;
                    $z = -$this->x;
                } else {
                    $x = -$this->z;
                    $z = $this->x;
                }
                $this->x = $x;
                $this->z = $z;
                break;
            case 'z':
                if ($clockwise) {
                    $x = -$this->y;
                    $y = $this->x;
                } else {
                    $x = $this->y;
                    $y = -$this->x;
                }
                $this->x = $x;
                $this->y = $y;
                break;
            default:
                break;
        }
    }

    public function rotateMulti(array $rotations): void
    {
        foreach ($rotations as $rotation) {
            [$axis, $clockwise] = $rotation;
            $this->rotate($axis, $clockwise);
        }
    }

    public function toArray(): array
    {
        return [
            $this->x,
            $this->y,
            $this->z,
        ];
    }
}