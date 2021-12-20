<?php
declare(strict_types=1);

namespace marvellanius\Advent\Y2021;

use marvellanius\Advent\Answer;
use marvellanius\Advent\AbstractDay;
use marvellanius\Advent\Y2021\Day19\Beacon;
use marvellanius\Advent\Y2021\Day19\Scanner;
use marvellanius\Advent\Y2021\Day19\ScannerMap;

final class Day19 extends AbstractDay
{
    public function run1(): Answer
    {
        $scanners = $this->getScanners();
        $foundScanners = [0 => $scanners[0]];

        // Voor iedere eerder gevonden scanner
        foreach ($foundScanners as $reference) {
            // Kijk of je een volgende scanner kan bepalen
            foreach ($scanners as $target) {
                $foundScanner = $reference->compare($target);
                if ($foundScanner) {
                    // Zo ja, sla de gevonden scanner op
                    $foundScanners[$foundScanner->getId()] = $foundScanner;
                    // En verwijder uit de lijst met te bepalen scanners
                    unset($scanners[$foundScanner->getId()]);
                }
            }
        }

        // GOAL: GET UNIQUE BEACONS
        // HOW? Roelie knows


        return new Answer("0");
    }

    public function run2(): Answer
    {
        return new Answer("Not implemented");
    }

    /** @return Scanner[] */
    private function getScanners(): array
    {
        $input = $this->getInputAsArray();

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

        $scanners = $this->generateOrientations($scanners);

        return $scanners;
    }

    private function generateOrientations(array $scanners): array
    {
        $rotatedScanners = [];

        $orientations = [
            [0, 0, 1],
            [0, 1, 0],
            [1, 0, 0],

            [0, 0, -1],
            [0, -1, 0],
            [-1, 0, 0],

            [0, 1, 1],
            [0, -1, 1],
            [0, 1, -1],
            [0, -1, -1],

            [1, 1, 0],
            [1, -1, 0],
            [-1, 1, 0],
            [-1, -1, 0],

            [1, 0, 1],
            [-1, 0, 1],
            [1, 0, -1],
            [-1, 0, -1],

            [1, 1, 1],
            [-1, 1, 1],
            [-1, -1, 1],
            [-1, 1, -1],
            [1, 1, -1],
            [1, -1, 1],
            [-1, 1, -1],
            [-1, -1, -1],
        ];

        foreach ($orientations as $orientation) {
            [$x, $y, $z] = $orientation;
            $rotate = [];
            foreach ($orientation as $axis) {
                if ($axis === 0) {
                    continue;
                }
                $clockwise = true;
                if ($axis < 0) {
                    $clockwise = false;
                }
                $rotate += [$axis, $clockwise];
            }
        }

        /** @var Scanner $scanner */
        foreach ($scanners as $scanner) {


            $rotatedScanners[] = $scanner;


            foreach ($orientations as $orientation) {
                $rotate = [];
                foreach ($orientation as $key => $axis) {
                    if ($axis === 0) {
                        continue;
                    }
                    $clockwise = true;
                    if ($axis < 0) {
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

                    $rotate[] = [$axis, $clockwise];
                }
                $rotatedScanners[] = $scanner->rotate($rotate);
            }

//            foreach (range(1,4) as $stepX) {
//
//                $rotatedScanner = $scanner->rotateX();
//                $rotatedScanners[] = $rotatedScanner;
//
//                foreach (range(1, 4) as $stepY) {
//
//                    $rotatedScanner = $rotatedScanner->rotateY();
//                    $rotatedScanners[] = $rotatedScanner;
//
//                    foreach (range(1, 4) as $stepZ) {
//                        $rotatedScanner = $rotatedScanner->rotateZ();
//                        $rotatedScanners[] = $rotatedScanner;
//                    }
//                }
//            }
//            $rotatedBeacons = [];
//            foreach ($rotatedScanners as $rscanner) {
//                $rotatedBeacons[] = array_map(static fn (Beacon $beacon) => $beacon->toArray(), $rscanner->getBeacons());
//            }
//            $rotatedBeacons = array_unique($rotatedBeacons);
//            foreach ($rotatedBeacons as $bea)
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