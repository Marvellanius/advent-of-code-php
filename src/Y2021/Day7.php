<?php
declare(strict_types=1);

namespace marvellanius\Advent\Y2021;

use marvellanius\Advent\Answer;
use marvellanius\Advent\AbstractDay;

final class Day7 extends AbstractDay
{
    public function run1(): Answer
    {
        $positions = $this->getInputAsArray(true, ",")[0];

        $minFuel = $this->determineMostEfficientAlignmentFuelCost($positions);

        return new Answer((string) $minFuel);
    }

    public function run2(): Answer
    {
        $positions = $this->getInputAsArray(true, ",")[0];

        $minFuel = $this->determineMostEfficientAlignmentFuelCost($positions, true);

        return new Answer((string) $minFuel);
    }

    private function determineMostEfficientAlignmentFuelCost(array $positions, bool $fuelCostIncreases = false): int
    {

        $minFuel = 0;
        foreach(range(min($positions), max($positions)) as $position) {
            $fuel = [];
            foreach($positions as $crab) {
                if ($fuelCostIncreases) {
                    $fuel[] = (int) (abs($position - $crab) * (0.5 * abs($position - $crab) + 0.5));
                } else {
                    $fuel[] = abs($position - $crab);
                }
            }
            $sumFuel = array_sum($fuel);
            if (!$minFuel) {
                $minFuel = $sumFuel;
            } else if ($sumFuel < $minFuel){
                $minFuel = $sumFuel;
            }
        }

        return $minFuel;
    }
}