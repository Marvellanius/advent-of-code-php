<?php
declare(strict_types=1);

namespace marvellanius\Advent\Y2021;

use marvellanius\Advent\Answer;
use marvellanius\Advent\AbstractDay;
use PhpParser\Node\Param;

final class Day15 extends AbstractDay
{
    private array $matrix = [];
    public function run1(): Answer
    {
        $input = $this->getInputAsArray(true, "\r\n");

        foreach ($input as $row) {
            $temp = str_split(trim($row[0]));
            $temp = array_map('intval', $temp);
            $this->matrix[] = $temp;
        }

        $result = $this->dijkstra();
        return new Answer((string) $result['distance']);
    }

    public function run2(): Answer
    {
        // This solution could stand some performance improvements; it runs for 50m 49s 256.456 ms
        $input = $this->getInputAsArray(true, "\r\n");

        foreach ($input as $row) {
            $temp = str_split(trim($row[0]));
            $temp = array_map('intval', $temp);
            $this->matrix[] = $temp;
        }
        $column_bound = count($this->matrix[0]);
        $row_bound = count(array_column($this->matrix, 0));
        // for all cells at starting point (0,0 - $c_bound,0 - 2*$c_bound,0)

        foreach ($this->matrix as $row_key => $row) {
            foreach ($row as $column_key => $column) {
                for ($i = 1; $i < 5; $i++) {
                    $newColumn = $column + $i;
                    if ($newColumn > 9) {
                        $newColumn -= 9;
                    }
                    $newRowKey = $i * $row_bound + $row_key;
                    $newColKey = $i * $column_bound + $column_key;
                    $this->matrix[$row_key][$newColKey] = $newColumn;
                    $this->matrix[$newRowKey][$column_key] = $newColumn;
                }
            }
        }

        foreach ($this->matrix as $row_key => $row) {
            if ($row_key >= $row_bound) {
                foreach ($row as $column_key => $column) {
                    for ($i = 1; $i < 5; $i++) {
                        $newColumn = $column + $i;
                        if ($newColumn > 9) {
                            $newColumn -= 9;
                        }
                        $newColKey = $i * $column_bound + $column_key;
                        $this->matrix[$row_key][$newColKey] = $newColumn;
                    }
                }
            }
        }

        $result = $this->dijkstra();
        return new Answer((string) $result['distance']);
    }

    private function dijkstra(): array
    {
        $matrix = $this->matrix;
        $c_bound = count($matrix[0]);
        $r_bound = count(array_column($matrix, 0));
        $distance = [];
        $previous = [];
        $set = [];

        foreach ($matrix as $row_key => $row) {
            foreach ($row as $column_key => $column) {
                $coordinate = implode(",", [$row_key, $column_key]);

                $set += [
                    $coordinate => [
                        'distance' => INF,
                        'previous' => null,
                    ],
                ];
            }
        }

        $set["0,0"] = [
            'distance' => 0,
            'previous' => null,
        ];

        $directions = [[0, 1], [1, 0]];

        while (!empty($set)) {
            $minDistance = INF;
            // get coordinate for lowest cost step
            foreach ($set as $key => $point) {
                if ($point['distance'] < $minDistance) {
                    $minDistance = $point['distance'];
                    $coordinate = $key;
                }
            }

            unset($set[$coordinate]);

            $index = explode(",", $coordinate);
            $row = (int) $index[0];
            $column = (int) $index[1];

            foreach ($directions as $direction) {
                $r_i = $row + $direction[0];
                $c_i = $column + $direction[1];
                $nextCoord = implode(",", [$r_i, $c_i]);
                if ($r_i >= 0 && $r_i < $r_bound
                    && $c_i >= 0 && $c_i < $c_bound
                    && (isset($set[$nextCoord]))
                ) {
                    $alt = $minDistance + $matrix[$r_i][$c_i];
                    if ($alt < $set[$nextCoord]['distance']) {
                        $set[$nextCoord]['distance'] = $alt;
                        $set[$nextCoord]['previous'] = $coordinate;

                        if ($nextCoord === implode(",", [$r_bound - 1, $c_bound - 1 ])) {
                            return $set[$nextCoord];
                        }
                    }
                }
            }
        }
        return [$distance, $previous];
    }
}