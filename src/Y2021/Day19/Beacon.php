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

    public function rotateX(bool $clockwise = true): void
    {
        $this->rotate('x', $clockwise);
    }

    public function rotateY(bool $clockwise = true): void
    {
        $this->rotate('y', $clockwise);
    }

    public function rotateZ(bool $clockwise = true): void
    {
        $this->rotate('z', $clockwise);
    }
    /*
           |  X  |  Y   |  Z    |
    Beacon | 300 |  200 |  100  |
     X  90 | 300 | -100 |  200  |
     X 180 | 300 | -200 | -100  |
     X -90 | 300 |  100 | -200  |
    */

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

    /**
    000
    011 | 0 -1 1 | 0 1 -1 | 0 -1 -1 |
    101 | -1 0 1 | 1 0 -1 | -1 0 -1 |
    110 | -1 1 0 | 1 -1 0 | -1 -1 0 |
    111 | -1 1 1 | -1 -1 1 | -1 1 -1 | 1 1 -1 | -1 -1 -1
    */

    // 001 : rotate over Z 90 deg: X becomes -Y and Y becomes X
    // 010 : rotate over Y 90 deg: Z becomes -X and X becomes Z
    // 100 : rotate over X 90 deg: Y becomes -Z and Z becomes Y



    // 00-1 : rotate over Z -90 deg: X becomes Y and Y becomes -X
    // 0-10 : rotate over Y -90 deg: Z becomes X and X becomes -Z
    // -100 : rotate over X -90 deg: Y becomes Z and Z becomes -Y

    // for 360deg X rotation:
    // 90deg:  X==X, Y == -Z, Z == Y
    // 180deg: X==X, Y == -Y, Z == -Z
    // 270deg: X==X, Y == Z, Z == -Y

    public function toArrayKeyed(): array
    {
        return [
            'x' => $this->x,
            'y' => $this->y,
            'z' => $this->z,
        ];
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