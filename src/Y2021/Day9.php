<?php
declare(strict_types=1);


namespace marvellanius\Advent\Y2021;

use marvellanius\Advent\AbstractDay;
use marvellanius\Advent\Answer;

final class Day9 extends AbstractDay
{

    public function run1(): Answer
    {
        $input = array_map(static fn ($item) => str_split(rtrim($item)), $this->getInputAsArray());

        $lowestPoints = [];
        // make matrix
        for ($i = 0; $i < count($input); $i++) {
            for ($j = 0; $j < count($input[$i]); $j++) {
                // put $rows[$i+1][$j], $rows[$i][$j-1] (if exists) and $rows[$i][$j+1] in an array of adjacent values
                $subMatrix = [$input[$i][$j], $input[$i-1][$j] ?? null, $input[$i][$j-1] ?? null, $input[$i][$j+1] ?? null, $input[$i+1][$j] ?? null];
                if ($input[$i][$j] != 9 && $input[$i][$j] === min(array_diff($subMatrix, [null]))) {
                    $lowestPoints[] = $input[$i][$j];
                }
            }
        }

        // if any of those values are lower, dump this record, and continue on the lower value
        $riskSum = count($lowestPoints) + array_sum($lowestPoints);
        return new Answer((string) $riskSum);
    }

    public function run2(): Answer
    {
        $input = array_map(static fn ($item) => str_split(rtrim($item)), $this->getInputAsArray());

        $lowestPoints = [];
        for ($i = 0; $i < count($input); $i++) {
            for ($j = 0; $j < count($input[$i]); $j++) {
                // put $rows[$i+1][$j], $rows[$i][$j-1] (if exists) and $rows[$i][$j+1] in an array of adjacent values
                $subMatrix = [$input[$i][$j], $input[$i-1][$j] ?? null, $input[$i][$j-1] ?? null, $input[$i][$j+1] ?? null, $input[$i+1][$j] ?? null];
                if ($input[$i][$j] != 9 && $input[$i][$j] === min(array_diff($subMatrix, [null]))) {
                    $lowestPoints[] = [$i, $j, $input[$i][$j]];
                }
            }
        }

        $basins = [];
        foreach ($lowestPoints as $lowestPoint) {
             $basins[] = $this->checkAdjacentCells($lowestPoint[0], $lowestPoint[1], $input);
        }
        rsort($basins);
        $maxBasins = array_slice($basins, 0, 3);

        // check from lowest point up to and including 8s, excluding 9s
        // dump count of elements in basin, multiply biggest 3

        return new Answer((string) array_product($maxBasins));
    }

    private function checkAdjacentCells(int $row, int $column, array &$matrix): int
    {
        $c_bound = count($matrix[0]);
        $r_bound = count(array_column($matrix, 0));

        $area = 1;

        $matrix[$row][$column] = null;

        $directions = [[-1, 0], [0, 1], [1, 0], [0, -1]];

        foreach ($directions as $direction) {
            $r_i = $row + $direction[0];
            $c_i = $column + $direction[1];
            if ($r_i >= 0 && $r_i < $r_bound && $c_i >= 0 && $c_i < $c_bound && $matrix[$r_i][$c_i] !== null && (int) $matrix[$r_i][$c_i] !== 9 ) {
                $area += $this->checkAdjacentCells($r_i, $c_i, $matrix);
            }
        }

        return $area;
    }
}