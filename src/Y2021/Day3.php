<?php
declare(strict_types=1);

namespace marvellanius\Advent\Y2021;

use marvellanius\Advent\Answer;
use marvellanius\Advent\AbstractDay;

/**
 * @author Dick van Viegen <dick@tweakers.net>
 */
class Day3 extends AbstractDay
{
    public function run1(): Answer
    {
        $array = $this->convertToPositionArray(array_map(static fn ($value) => trim($value), $this->getInputAsArray()));

        $gamma = implode(array_map([$this, 'determineMostCommonValue'], $array));
        $epsilon = implode(array_map([$this, 'determineLeastCommonValue'], $array));

        return new Answer((string) (bindec($gamma) * bindec($epsilon)));
    }

    public function run2(): Answer
    {
        $array = array_map(static fn ($value) => trim($value), $this->getInputAsArray());

        $ogr = $this->determineRating($array, strlen($array[0]), 'determineMostCommonValue');
        $csr = $this->determineRating($array, strlen($array[0]), 'determineLeastCommonValue');

        return new Answer((string) (bindec($ogr) * bindec($csr)));
    }

    private function determineRating(array $array, int $length, string $callable): string
    {
        for ($key = 0; $key < $length; $key++) {
            if (count($array) > 1) {
                $array = $this->filterArrayForValues($array, $this->$callable($this->convertToPositionArray($array)[$key]), $key);
            }
        }
        return array_values($array)[0];
    }

    private function filterArrayForValues(array $array, int $value, int $key): array
    {
        return array_filter($array, static fn ($item) => (int) $item[$key] === $value);
    }

    private function convertToPositionArray(array $array): array
    {
        $positionArray = [];

        $binArray = array_map(static fn ($value) => str_split($value), $array);

        foreach ($binArray as $key => $bin) {
            foreach ($bin as $k => $v) {
                $positionArray[$k][$key] = $v;
            }
        }

        return array_map(static fn ($v) => array_count_values($v), $positionArray);
    }

    private function determineMostCommonValue(array $array): int
    {
        return array_values($array)[0] === array_values($array)[1] ? 1 : array_keys($array, max($array), true)[0];
    }

    private function determineLeastCommonValue(array $array): int
    {
        return array_values($array)[0] === array_values($array)[1] ? 0 : array_keys($array, min($array), true)[0];
    }
}