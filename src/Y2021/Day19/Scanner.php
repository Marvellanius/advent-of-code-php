<?php
declare(strict_types=1);


namespace marvellanius\Advent\Y2021\Day19;

final class Scanner
{
    private ?int $x = null;
    private ?int $y = null;
    private ?int $z = null;

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

    public function setPosition(int $x, int $y, int $z): void
    {
        $this->x = $x;
        $this->y = $y;
        $this->z = $z;
    }

    public function getPosition(): array
    {
        return [
            'x' => $this->x,
            'y' => $this->y,
            'z' => $this->z
        ];
    }

    public function getBeacons(): array
    {
        return $this->beacons;
    }

    public function addBeacon(Beacon $beacon): void
    {
        $this->beacons[] = $beacon;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function compare(Scanner $scanner): ?Scanner
    {
        foreach ($this->getBeaconsAbsolute() as $anchorKey => $anchorBeacon) {
            // update other beacons to reflect new anchorpoint
            $relativeBeacons = $this->generateBeaconsRelativeTo($anchorBeacon);
            foreach ($scanner->getBeacons() as $targetBeacon) {
                $targetRelativeBeacons = $scanner->generateBeaconsRelativeTo($targetBeacon);
                $count = 0;
                $foundBeaconMap = [];
                foreach ($targetRelativeBeacons as $key => $beacon) {
                    $found = array_search($beacon, $relativeBeacons);
                    if ($found !== false) {
                        $count++;
                        $foundBeaconMap += [$key => $found];
                    }

                    if ($count >= 12) {
                        return $scanner->setPositionRelativeTo($this, $foundBeaconMap);
                    }
                }
            }
        }
        return null;
    }

    public function getBeaconsRelativeTo(Scanner|Beacon $target, bool $array): array
    {
        $relativeBeacons = [];

        foreach ($this->beacons as $beacon) {
            if ($target instanceof self) {
                $beacon = Beacon::createFromCoordinates(
                    $beacon->x + $target->x,
                    $beacon->y + $target->y,
                    $beacon->z + $target->z
                );
            }

            if ($target instanceof Beacon) {
                $beacon = Beacon::createFromCoordinates(
                    $beacon->x - $target->x,
                    $beacon->y - $target->y,
                    $beacon->z - $target->z
                );
            }

            if ($array) {
                $beacon = $beacon->toArray();
            }
            $relativeBeacons[] = $beacon;
        }

        return $relativeBeacons;
    }

    public function getBeaconsAbsolute(bool $array = false): array
    {
        $relativeBeacons = [];

        foreach ($this->beacons as $beacon) {
            $beacon = Beacon::createFromCoordinates(
                $beacon->x - $this->x,
                $beacon->y - $this->y,
                $beacon->z - $this->z
            );

            if ($array) {
                $beacon = $beacon->toArray();
            }
            $relativeBeacons[] = $beacon;
        }

        return $relativeBeacons;
    }

    private function setPositionRelativeTo(Scanner $scanner, array $beaconMap): Scanner
    {
        $position = [];
        foreach ($beaconMap as $thisIndex => $scannerIndex) {
            $thisBeacon = $this->getBeacons()[$thisIndex];
            $scannerBeacon = $scanner->getBeacons()[$scannerIndex];
            $checkPosition = [
                $scannerBeacon->x - $thisBeacon->x,
                $scannerBeacon->y - $thisBeacon->y,
                $scannerBeacon->z - $thisBeacon->z,
            ];

            if (empty($position)) {
                $position = $checkPosition;
                continue;
            }

            if ($checkPosition === $position) {
                $viaScannerPosition = $scanner->getPosition();
                $this->x = $position[0] + $viaScannerPosition['x'];
                $this->y = $position[1] + $viaScannerPosition['y'];
                $this->z = $position[2] + $viaScannerPosition['z'];
                break;
            }
        }

        return $this;
    }

    private function generateBeaconsRelativeTo(Beacon $anchorBeacon): array
    {
        $relativeBeacons = [];

        $x = $this->x ?? 0;
        $y = $this->y ?? 0;
        $z = $this->z ?? 0;
        foreach ($this->beacons as $beacon) {
            $relativeBeacons[] = Beacon::createFromCoordinates(
                $beacon->x - $x - $anchorBeacon->x,
                $beacon->y - $y - $anchorBeacon->y,
                $beacon->z - $z - $anchorBeacon->z
            )->toArray();
        }

        return $relativeBeacons;
    }

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
}