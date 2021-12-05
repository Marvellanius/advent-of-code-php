<?php
declare(strict_types=1);

namespace marvellanius\Advent\Y2021;

use marvellanius\Advent\Answer;
use marvellanius\Advent\AbstractDay;

final class Day5 extends AbstractDay
{
    public function run1(): Answer
    {
        $input = array_map(static fn ($value) => rtrim($value) ,$this->getInputAsArray());
        $lines = [];
        foreach ($input as $line) {
            $points = preg_split("/[-\s>]+/", $line);
            $start = explode(",", $points[0]);
            $end = explode(",", $points[1]);

            if ($start[0] !== $end[0] && $start[1] !== $end[1]) {
                continue;
            }
            $lines[] = [$start, $end];
        }

        return new Answer((string) $this->bruteForceIntersectionsForLines($lines));
    }

    public function run2(): Answer
    {
        $input = array_map(static fn ($value) => rtrim($value) ,$this->getInputAsArray());
        $lines = [];
        foreach ($input as $line) {
            $points = preg_split("/[-\s>]+/", $line);
            $start = explode(",", $points[0]);
            $end = explode(",", $points[1]);

            $lines[] = [$start, $end];
        }

        return new Answer((string) $this->bruteForceIntersectionsForLines($lines));
    }

    private function bruteForceIntersectionsForLines(array $lines): int
    {
        $points = [];

        foreach ($lines as $line) {
            [[$s_x, $s_y], [$e_x, $e_y]] = $line;

            // Diagonal
            if ($s_x !== $e_x && $s_y !== $e_y) {
                foreach (range($s_x, $e_x) as $step => $value) {
                    if ($s_x > $e_x) {
                        $x = $s_x - $step;
                    } else {
                        $x = $s_x + $step;
                    }

                    if ($s_y > $e_y) {
                        $y = $s_y - $step;
                    } else {
                        $y = $s_y + $step;
                    }

                    $key = "({$x},{$y})";
                    $count = ($points[$key] ?? 0) + 1;
                    $points[$key] = $count;
                }
            }

            // Horizontal
            if ($s_x === $e_x) {
                $x = $s_x;
                foreach (range($s_y, $e_y) as $y) {
                    $key = "({$x},{$y})";
                    $count = ($points[$key] ?? 0) + 1;
                    $points[$key] = $count;
                }
            }

            // Vertical
            if ($s_y === $e_y) {
                $y = $s_y;
                foreach (range($s_x, $e_x) as $x) {
                    $key = "({$x},{$y})";
                    $count = ($points[$key] ?? 0) + 1;
                    $points[$key] = $count;
                }
            }
        }
        $count = 0;
        foreach ($points as $point) {
            if ($point > 1) {
                $count++;
            }
        }

        return $count;
    }
}