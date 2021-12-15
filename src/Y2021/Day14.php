<?php
declare(strict_types=1);

namespace marvellanius\Advent\Y2021;

use marvellanius\Advent\Answer;
use marvellanius\Advent\AbstractDay;
use PhpParser\Node\Param;

final class Day14 extends AbstractDay
{
    public function run1(): Answer
    {
        $input = $this->getInputAsArray();
        $polymer = str_split(trim($input[0]));
        $rules = [];
        foreach  (array_slice($input, 1) as $line) {
            if (trim($line) === "") {
                continue;
            }
            $rule = explode(' -> ', rtrim($line));

            $rules[$rule[0]] = $rule[1];
        }

        foreach (range(0, 9) as $step) {
            $newSegment = [];
            foreach ($polymer as $key => $letter) {
                $prev = $polymer[$key-1] ?? null;

                if ($prev) {
                    $pair = $polymer[$key-1] . $letter;

                    $rule = $rules[$pair] ?? null;

                    if ($rule) {
                        $newSegment = [...$newSegment, $rule, $letter];
                    }
                }
            }
            $polymer = [$polymer[0], ...$newSegment];
        }
        $polymerCount = array_count_values($polymer);
        $result = max($polymerCount) - min($polymerCount);
        return new Answer((string) $result);
    }

    public function run2(): Answer
    {
        $input = $this->getInputAsArray();
        $polymer = str_split(trim($input[0]));
        $rules = [];
        $pairs = [];
        foreach  (array_slice($input, 1) as $line) {
            if (trim($line) === "") {
                continue;
            }
            $rule = explode(' -> ', rtrim($line));

            $rules[$rule[0]] = ['insert' => $rule[1], 'count' => 0];
            $pairs[] = $rule[0];
        }
        $polymers = [];
        $pairCount = array_fill_keys($pairs, 0);
        foreach ($pairCount as $pair => $count) {
            if (! in_array($pair[0], $polymers)) {
                $polymers[] = $pair[0];
            }
            if (! in_array($pair[1], $polymers)) {
                $polymers[] = $pair[1];
            }
        }
        $polymerCount = array_fill_keys($polymers, 0);
        foreach ($polymer as $key => $letter) {
            $polymerCount[$letter]++;

            $prev = $polymer[$key-1] ?? null;
            if ($prev) {
                $pair = $prev . $letter;
                $pairCount[$pair]++;
            }
        }

        foreach (range(0, 39) as $step) {
            $pairsToUpdate = [];
            foreach ($pairCount as $pair => $count) {
                if ($count === 0) {
                    continue;
                }
                $rule = $rules[$pair];
                $polymerCount[$rule['insert']] += $count;
                $pairsToUpdate[] = [$pair, $pair[0] . $rule['insert'], $rule['insert'] . $pair[1], $count];
            }
            foreach ($pairsToUpdate as $pairs) {
                $pairCount[$pairs[0]] -= $pairs[3];
                $pairCount[$pairs[1]] += $pairs[3];
                $pairCount[$pairs[2]] += $pairs[3];
            }
        }
        $result = max($polymerCount) - min($polymerCount);
        return new Answer((string) $result);
    }
}