<?php
declare(strict_types=1);


namespace marvellanius\Advent\Y2021\Day19;

final class Scanner
{
    private int $x;
    private int $y;
    private int $z;

    public static function fromArray(array $input): self
    {
        $id = $input['id'];
        $beacons = $input['beacons'];
        return new self($id, $beacons);
    }

    public function __construct(
        private int $id,
        private array $beacons,
    ) {
    }

    public function getBeacons(): array
    {
        return $this->beacons;
    }

    public function addBeacon(Beacon $beacon): void
    {
        $this->beacons[] = $beacon;
    }

    public function getMinimumForCoordinate(string $coordinate): int
    {
        return min(array_map(static fn (Beacon $beacon) => $beacon->$coordinate, $this->beacons));
    }

    public function getMaximumForCoordinate(string $coordinate): int
    {
        return max(array_map(static fn (Beacon $beacon) => $beacon->$coordinate, $this->beacons));
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function compare(Scanner $scanner): ?Scanner
    {
        foreach ($this->beacons as $anchorBeacon) {
            // update other beacons to reflect new anchorpoint
            $relativeBeacons = $this->generateBeaconsRelativeTo($anchorBeacon);
            foreach ($scanner->getBeacons() as $targetBeacon) {
                $targetRelativeBeacons = $scanner->generateBeaconsRelativeTo($targetBeacon);
                $count = 0;
                foreach ($targetRelativeBeacons as $beacon) {
                    if (in_array($beacon, $relativeBeacons)) {
                        $count++;
                    }

                    if ($count >= 12) {
                        return $scanner;
                    }
                }
            }
        }
        return null;
    }

    private function generateBeaconsRelativeTo(Beacon $anchorBeacon): array
    {
        $relativeBeacons = [];

        foreach ($this->beacons as $beacon) {
            $relativeBeacons[] = Beacon::createFromCoordinates(
                $beacon->x - $anchorBeacon->x,
                $beacon->y - $anchorBeacon->y,
                $beacon->z - $anchorBeacon->z
            )->toArray();
        }

        return $relativeBeacons;
    }

    // rotate X * 4, Y * 4, Z * 2
    /*
     * foreach (range(1,4) as $stepX) {
     *  foreach (range(1,4) as $stepY) {
     *   foreach (range(1,2) as $stepZ) {
     *
     *   }
     *  }
     * }
     */

    public function rotate(array $rotation): self
    {
        $rotatedScanner = new self($this->id, []);
        /** @var Beacon $beacon */
        foreach ($this->beacons as $beacon) {
            $rotatedBeacon = Beacon::createFromCoordinates(...$beacon->toArray());
            $rotatedBeacon->rotateMulti($rotation);
            $rotatedScanner->addBeacon($rotatedBeacon);
        }
        return $rotatedScanner;
    }

    public function rotateX(): self
    {
        $rotatedScanner = new self($this->id, []);
        /** @var Beacon $beacon */
        foreach ($this->beacons as $beacon) {
            $rotatedBeacon = Beacon::createFromCoordinates(...$beacon->toArray());
            $rotatedBeacon->rotateX();
            $rotatedScanner->addBeacon($rotatedBeacon);
        }
        return $rotatedScanner;
    }

    public function rotateY(): self
    {
        $rotatedScanner = new self($this->id, []);
        /** @var Beacon $beacon */
        foreach ($this->beacons as $beacon) {
            $rotatedBeacon = Beacon::createFromCoordinates(...$beacon->toArray());
            $rotatedBeacon->rotateY();
            $rotatedScanner->addBeacon($rotatedBeacon);
        }
        return $rotatedScanner;
    }

    public function rotateZ(): self
    {
        $rotatedScanner = new self($this->id, []);
        /** @var Beacon $beacon */
        foreach ($this->beacons as $beacon) {
            $rotatedBeacon = Beacon::createFromCoordinates(...$beacon->toArray());
            $rotatedBeacon->rotateZ();
            $rotatedScanner->addBeacon($rotatedBeacon);
        }
        return $rotatedScanner;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'beacons' => $this->beacons,
        ];
    }
}