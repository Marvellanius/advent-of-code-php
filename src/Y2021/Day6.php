<?php
declare(strict_types=1);

namespace marvellanius\Advent\Y2021;

use marvellanius\Advent\Answer;
use marvellanius\Advent\AbstractDay;

final class Day6 extends AbstractDay
{
    public function run1(): Answer
    {
        // each iteration:
        // lanternfish -1
        // if lanternfish === 0
        // append new fish: 8

        $fish = array_map(static fn ($v) => (int) rtrim($v), $this->getInputAsArray(true, ",")[0]);

        $result = $this->findAmountOfFishAfterDays(80, $fish);

        return new Answer((string) $result);
    }

    public function run2(): Answer
    {
        $fish = array_map(static fn ($v) => (int) rtrim($v), $this->getInputAsArray(true, ",")[0]);

        $result = $this->findAmountOfFishAfterDaysGrouped(256, $fish);

        return new Answer((string) $result);
    }

    private function findAmountOfFishAfterDays(int $days, array $fish): int
    {
        $daysToRun = $days;

        for ($day = 1; $day <= $daysToRun; $day++) {
            $newFish = [];
            foreach ($fish as $k => $v) {
                if ($v === 0) {
                    $fish[$k] = 6;
                    $newFish[] = 8;
                } else if ($v > 0) {
                    $fish[$k]--;
                }
            }
            $fish = [...$fish, ...$newFish];
        }

        return count($fish);
    }

    private function findAmountOfFishAfterDaysGrouped(int $days, array $fish): int
    {
        $daysToRun = $days;

        $groupedFishies = array_fill(0, 9, 0);

        foreach ($fish as $f) {
            $groupedFishies[$f]++;
        }

        for ($day = 1; $day <= $daysToRun; $day++) {
            // save key 0
            $readyToBreed = $groupedFishies[0];
            // loop over other groups, and increment previous key
            foreach (range(1, 8) as $group) {
                $groupedFishies[$group-1] = $groupedFishies[$group];
            }

            // reset key 0 fishies to key 6
            $groupedFishies[6] += $readyToBreed;
            // add key 0 fishies to key 8
            $groupedFishies[8] = $readyToBreed;
        }

        return array_sum($groupedFishies);
    }
}