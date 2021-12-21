<?php
declare(strict_types=1);

namespace marvellanius\Advent\Y2021;

use marvellanius\Advent\Answer;
use marvellanius\Advent\AbstractDay;
use marvellanius\Advent\Y2021\Day19\Beacon;
use marvellanius\Advent\Y2021\Day19\Scanner;

final class Day19 extends AbstractDay
{
    public function run1(): Answer
    {
        [$originScanner, $scanners] = $this->getScanners();
        $originScanner->setPosition(0, 0, 0);

        $foundScanners = $this->determineScannerPositions($originScanner, $scanners);

        $foundBeacons = [];
        foreach ($foundScanners as $beaconList) {
            $absoluteBeacons = $beaconList->getBeaconsRelativeTo($beaconList, false);
            $implodedBeacons = array_map(static fn (Beacon $beacon) => implode(',', $beacon->toArray()), $absoluteBeacons);
            $foundBeacons = [...$foundBeacons, ...$implodedBeacons];
        }

        $uniqueBeacons = array_unique($foundBeacons);

        return new Answer((string) count($uniqueBeacons));
    }

    public function run2(): Answer
    {
        [$originScanner, $scanners] = $this->getScanners();
        $originScanner->setPosition(0, 0, 0);

        $foundScanners = $this->determineScannerPositions($originScanner, $scanners);

        $maxDistance = 0;
        foreach ($foundScanners as $origin) {
            $originPosition = $origin->getPosition();
            foreach ($foundScanners as $target) {
                $targetPosition = $target->getPosition();
                $diffX = abs($originPosition['x'] - $targetPosition['x']);
                $diffY = abs($originPosition['y'] - $targetPosition['y']);
                $diffZ = abs($originPosition['z'] - $targetPosition['z']);

                $manhattan = $diffX + $diffY + $diffZ;

                $maxDistance = max($maxDistance, $manhattan);
            }
        }

        return new Answer((string) $maxDistance);
    }

    private function determineScannerPositions(Scanner $originScanner, array $scanners): array
    {
        $foundScanners = [0 => $originScanner];

        while (!empty($scanners)) {
            // For every scanner with a concrete location
            /** @var Scanner $reference */
            foreach ($foundScanners as $reference) {
                // See if you can determine the position of another scanner
                /** @var Scanner $target */
                foreach ($scanners as $target) {
                    $foundScanner = $reference->compare($target);
                    if ($foundScanner) {
                        // If you succeeded, save the scanner
                        $foundScanners[$foundScanner->getId()] = $foundScanner;
                        // And remove it from the list of scanners that still need to be determined
                        $foundScannerList = array_filter($scanners, static fn (Scanner $scanner) => $scanner->getId() === $foundScanner->getId());
                        foreach ($foundScannerList as $key => $scanner) {
                            unset($scanners[$key]);
                        }
                        break;
                    }
                }
            }
        }

        return $foundScanners;
    }

    /** @return array(Scanner, Scanner[]) */
    private function getScanners(): array
    {
        $input = $this->getInputAsArray();

        $scanners = [];
        foreach ($input as $line) {
            $line = trim($line);
            if (str_contains($line, '--- scanner')) {
                $id = (int) str_replace('-', '', filter_var($line, FILTER_SANITIZE_NUMBER_INT));
                $scanner = new Scanner($id, []);
                $scanners[$id] = $scanner;
                continue;
            }
            if ($line === "") {
                continue;
            }
            $scanner->addBeacon($this->convertLineToBeacon($line));
        }

        $rotatedScanners = $this->generateOrientations(array_slice($scanners, 1));

        return [$scanners[0], $rotatedScanners];
    }

    private function generateOrientations(array $scanners): array
    {
        $rotatedScanners = [];

        // each orientation is represented by rotations of (a multiple of) 90 degrees along a (set of) axes [x, y, z]
        $orientations = [
            // pure x-rotations (also: heading)
            [1, 0, 0],
            [2, 0, 0],
            [-1, 0, 0],
            // y-based rotations (also: attitude)
            [0, 1, 0],
            [1, 1, 0],
            [2, 1, 0],
            [-1, 1, 0],
            // counterclockwise y-rotations
            [0, -1, 0],
            [1, -1, 0],
            [2, -1, 0],
            [-1, -1, 0],
            // z-based rotations (also: bank)
            [0, 0, 1],
            [1, 0, 1],
            [2, 0, 1],
            [-1, 0, 1],
            // 180-degree z-based rotations
            [0, 0, 2],
            [1, 0, 2],
            [2, 0, 2],
            [-1, 0, 2],
            // counter-clockwise z-rotations
            [0, 0, -1],
            [1, 0, -1],
            [2, 0, -1],
            [-1, 0, -1],
        ];

        /** @var Scanner $scanner */
        foreach ($scanners as $scanner) {

            $rotatedScanners[] = $scanner;
            foreach ($orientations as $orientation) {
                $rotate = [];
                foreach ($orientation as $key => $amount) {
                    if ($amount === 0) {
                        continue;
                    }
                    $clockwise = true;
                    if ($amount < 0) {
                        $clockwise = false;
                    }
                    switch ($key) {
                        case 0:
                            $axis = 'x';
                            break;
                        case 1:
                            $axis = 'y';
                            break;
                        case 2:
                            $axis = 'z';
                            break;
                    }
                    if ($amount === 2) {
                        $rotate[] = [$axis, $clockwise];
                    }
                    $rotate[] = [$axis, $clockwise];
                }
                $rotatedScanners[] = $scanner->rotate($rotate);
            }
        }

        return $rotatedScanners;
    }

    private function convertLineToBeacon(string $input): Beacon
    {
        $line = explode(",", $input);
        if (count($line) < 3) {
            [$x, $y] = $line;
            $z = 0;
        } else {
            [$x, $y, $z] = $line;
        }
        return Beacon::createFromCoordinates((int) $x, (int) $y, (int) $z);
    }
}