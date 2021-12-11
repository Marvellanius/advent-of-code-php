<?php
declare(strict_types=1);

namespace marvellanius\Advent\Y2021;

use marvellanius\Advent\AbstractDay;
use marvellanius\Advent\Answer;

/**
 * @author Dick van Viegen <dick@tweakers.net>
 */
class Day11 extends AbstractDay
{

    public function run1(): Answer
    {
        $input = array_map(static fn ($item) => str_split(rtrim($item)), $this->getInputAsArray());

        foreach (range(0, 9) as $r_i) {
            foreach (range(0, 9) as $c_i) {
                $matrix[$r_i][$c_i] = (int) $input[$r_i][$c_i];
            }
        }

        $flashes = 0;
        for ($i = 0; $i < 100; $i++) {
            $visitedCoords = [];
            $toFlash = [];

            for ($r = 0; $r < 10; $r++) {

                for ($c = 0; $c < 10; $c++) {

                    if ($matrix[$r][$c] < 10) {
                        $matrix[$r][$c] += 1;
                    }
                }
                $toFlash[] = array_filter($matrix[$r], static fn ($item) => $item === 10 );
            }

            foreach ($toFlash as $r_i => $row) {
                foreach ($row as $c_i => $value) {
                    $flashes += $this->checkAdjacentCells($r_i, $c_i, $matrix, $visitedCoords);
                }
            }
        }

        return new Answer((string) $flashes);
    }

    public function run2(): Answer
    {
        $input = array_map(static fn ($item) => str_split(rtrim($item)), $this->getInputAsArray());

        foreach (range(0, 9) as $r_i) {
            foreach (range(0, 9) as $c_i) {
                $matrix[$r_i][$c_i] = (int) $input[$r_i][$c_i];
            }
        }

        $flashes = 0;
        $step = 0;
        while (true) {
            $visitedCoords = [];
            $toFlash = [];

            for ($r = 0; $r < 10; $r++) {

                for ($c = 0; $c < 10; $c++) {

                    if ($matrix[$r][$c] < 10) {
                        $matrix[$r][$c] += 1;
                    }
                }
                $toFlash[] = array_filter($matrix[$r], static fn ($item) => $item === 10 );
            }

            foreach ($toFlash as $r_i => $row) {
                foreach ($row as $c_i => $value) {
                    $flashes += $this->checkAdjacentCells($r_i, $c_i, $matrix, $visitedCoords);
                }
            }

            $step++;
            if (count($visitedCoords) === 100) {
                break;
            }
        }

        return new Answer((string) $step);
    }

    private function checkAdjacentCells(int $row, int $column, array &$matrix, array &$visitedCoords): int
    {
        $c_bound = count($matrix[0]);
        $r_bound = count(array_column($matrix, 0));

        $flashes = 1;
        $matrix[$row][$column] = 0;
        $visitedCoords[] = [$row, $column];

        $directions = [[-1, 0], [0, 1], [1, 0], [0, -1], [-1, -1], [-1, 1], [1, -1], [1, 1]];

        foreach ($directions as $direction) {
            $r_i = $row + $direction[0];
            $c_i = $column + $direction[1];
            if ($r_i >= 0 && $r_i < $r_bound
                && $c_i >= 0 && $c_i < $c_bound
                && ! in_array([$r_i,$c_i], $visitedCoords)
            ) {
                $matrix[$r_i][$c_i] += 1;
                if ($matrix[$r_i][$c_i] === 10) {
                    $flashes += $this->checkAdjacentCells($r_i, $c_i, $matrix, $visitedCoords);
                }
            }
        }

        return $flashes;
    }
}