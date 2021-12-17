<?php
declare(strict_types=1);

namespace marvellanius\Advent\Y2021;

use marvellanius\Advent\Answer;
use marvellanius\Advent\AbstractDay;

final class Day17 extends AbstractDay
{
    public function run1(): Answer
    {
        $input = trim(str_replace('target area: ', '', $this->getInputAsArray()[0]));
        $ranges = explode(', ', $input);
        foreach ($ranges as $range) {
            $stringRange = explode('..', substr($range, 2));
            $targetArea[$range[0]] = array_map('intval', $stringRange);
        }

        // each step:
        // x-pos increases by x-velocity
        // y-pos increases by y-velocity
        // x-velocity decreases by 1 if > 0, increases by 1 if < 0 and stays 0 if === 0
        // y-velocity decreases by 1

        // Shoot probe from 0,0
        // Given target area is between targetArea['x'][0], targetArea['x'][1] and targetArea['y'][0], targetArea['y'[1]
        // What is the highest possible you can shoot the probe (max Y-pos)
        $minX = 0;
        $minXVelocity = 0;
        for ($i = 1; $minX < min($targetArea['x']); $i++) {
            $minX += $i;
            $minXVelocity = $i;
        }

        // cur_y_velocity = starting_y_velocity - 1 * (x_step)

        // ypos = start_y_pos + start_y_v + (-1 * step)
        // targetpos - 1 + -(-1 * step)
        // velocity = targetpos - (1 + step)

        $yVelocities = [];
        foreach (range(min($targetArea['y']), max($targetArea['y'])) as $targetPos) {
            $step = 1;
            $velocity = 0;
            while (0 + $velocity - $step >= min($targetArea['y'])) {
                $velocity = -$targetPos + (-1 * $step);
                $step++;
                $yVelocities[] = $velocity;
            }
        }
        $maxYVelocity = max($yVelocities);

        $shot = $this->shootProbe($targetArea, $minXVelocity, $maxYVelocity);
        $maxY = $shot['max'][1];

        return new Answer((string) $maxY);
    }

    public function run2(): Answer
    {
        $input = trim(str_replace('target area: ', '', $this->getInputAsArray()[0]));
        $ranges = explode(', ', $input);
        foreach ($ranges as $range) {
            $stringRange = explode('..', substr($range, 2));
            $targetArea[$range[0]] = array_map('intval', $stringRange);
        }

        // each step:
        // x-pos increases by x-velocity
        // y-pos increases by y-velocity
        // x-velocity decreases by 1 if > 0, increases by 1 if < 0 and stays 0 if === 0
        // y-velocity decreases by 1

        $minX = 0;
        $minXVelocity = 0;
        for ($i = 1; $minX < min($targetArea['x']); $i++) {
            $minX += $i;
            $minXVelocity = $i;
        }
        $xVelocities = [];
        foreach (range($minXVelocity, max($targetArea['x'])) as $xVelocity) {
            $step = 0;
            $xpos = 0;
            while ($xpos <= max($targetArea['x'])) {
                $xpos += max(0, $xVelocity - $step);
                $step++;

                if (min($targetArea['x']) <= $xpos && $xpos <= max($targetArea['x'])) {
                    $xVelocities[] = $xVelocity;
                    break;
                }
            }
        }
        $xVelocities = array_unique($xVelocities);
        // cur_y_velocity = starting_y_velocity - 1 * (x_step)

        // ypos = start_y_pos + start_y_v + (-1 * step)
        // targetpos - 1 + -(-1 * step)
        // velocity = targetpos - (1 + step)

        $yVelocities = range(min($targetArea['y']), abs(min($targetArea['y']))-1);

        $maxYVelocity = max($yVelocities);
        $count = 0;
        foreach ($xVelocities as $xVel) {
            foreach ($yVelocities as $yVel) {
                $shot = $this->shootProbe($targetArea, $xVel, $yVel);
                if ($shot['hit']) {
                    $count++;
                }
            }
        }

        return new Answer((string) $count);
    }

    private function shootProbe(array $targetArea, int $xVelocity, int $yVelocity): array
    {
        $startingVelocity = [$xVelocity, $yVelocity];
        $targetHit = false;
        $maxYPos = 0;
        $xPos = 0;
        $yPos = 0;
        while (! $targetHit) {
            $xPos += $xVelocity;
            $yPos += $yVelocity;

            if ($yPos > $maxYPos) {
                $maxYPos = $yPos;
            }

            $xVelocity = match(true) {
                $xVelocity > 0 => $xVelocity - 1,
                $xVelocity < 0 => $xVelocity + 1,
                $xVelocity === 0 => 0
            };

            $yVelocity -= 1;

//            echo "Current position {$xPos},{$yPos} \r\n";
            if (in_array($xPos, range(min($targetArea['x']),max($targetArea['x']))) && in_array($yPos, range(min($targetArea['y']),max($targetArea['y'])))) {
                $targetHit = true;
            }
            // If current position is 'out of bounds', stop the loop
            if ($xPos > $targetArea['x'][1] || $yPos < $targetArea['y'][0]) {
                break;
            }
        }
        return [
            'targetArea' => $targetArea,
            'velocity' => $startingVelocity,
            'hit' => $targetHit,
            'max' => [0, $maxYPos],
            'finalPos' => [$xPos, $yPos]
        ];
    }
}