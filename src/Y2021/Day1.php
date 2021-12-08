<?php

namespace marvellanius\Advent\Y2021;

use marvellanius\Advent\Answer;
use marvellanius\Advent\AbstractDay;

final class Day1 extends AbstractDay
{
    public function run1(): Answer
    {
        $array = array_map(static fn ($value) => trim($value), $this->getInputAsArray());

        return new Answer($this->getComparisonSuccessCount($array, 'isGreaterThanPrevious'));
    }

    public function run2(): Answer
    {
        $array = array_map(static fn ($value) => trim($value), $this->getInputAsArray());
        $windowLength = 3;

        // O(2n)?
//        $arrayOfSums = [];
//        for ($key = 0; $key <= count($array) - $windowLength; $key++) {
//            $arrayOfSums[] = array_reduce(
//                array_slice($array, $key, $windowLength),
//                static fn ($carry, $item) => $carry += $item
//            );
//        }
//        return new Answer($this->getComparisonSuccessCount($arrayOfSums, 'isGreaterThanPrevious'));

        // O(n)
        $previousSum = array_sum(array_slice($array, 0, $windowLength));
        $windowSum = $previousSum;

        $greaterThanCount = 0;
        for ($i = $windowLength; $i < count($array); $i++)
        {
            $windowSum += $array[$i] - $array[$i - $windowLength];
            if ($windowSum > $previousSum) {
                $greaterThanCount++;
            }
            $previousSum = $windowSum;
        }

        return new Answer((string) $greaterThanCount);
    }

    private function getComparisonSuccessCount(array $array, string $callback): int
    {
        $count = 0;
        foreach ($array as $key => $value) {
            if ($this->$callback($key, $array)) {
                $count++;
            }
        }

        return $count;
    }

    private function isGreaterThanPrevious($key, $array): bool
    {
        $previous = $array[$key-1] ?? null;

        return $previous && $array[$key] > $previous;
    }
}