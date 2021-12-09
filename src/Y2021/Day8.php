<?php
declare(strict_types=1);


namespace marvellanius\Advent\Y2021;

use marvellanius\Advent\AbstractDay;
use marvellanius\Advent\Answer;

final class Day8 extends AbstractDay
{

    public function run1(): Answer
    {
        $input = array_map(static fn ($item) => explode(" ", trim($item[1])) , $this->getInputAsArray(true, "|"));
        $count = 0;
        foreach ($input as $digit) {
            foreach ($digit as $segment) {
                if (in_array(strlen($segment), [2,3,4,7])) {
                    $count++;
                }
            }
        }

        return new Answer((string)$count);
    }

    public function run2(): Answer
    {
        $input = $this->getInputAsArray(true, "|");
        $signals = array_map(static fn ($item) => trim($item[0]), $input);

        foreach ($signals as $key => $line) {
            $sortedSegments = [];
            foreach (explode(" ", $line) as $segments) {
                $segmentArray = str_split($segments);
                sort($segmentArray);
                switch (count($segmentArray)) {
                    case 2:
                        $foundNumbers[$key][1] = $segmentArray;
                        break;
                    case 3:
                        $foundNumbers[$key][7] = $segmentArray;
                        break;
                    case 4:
                        $foundNumbers[$key][4] = $segmentArray;
                        break;
                    case 7:
                        $foundNumbers[$key][8] = $segmentArray;
                        break;
                }
                $sortedSegments[] = $segmentArray;
            }

            $len6 = array_filter($sortedSegments, static fn ($item) => count($item) === 6);
            $len5 = array_filter($sortedSegments, static fn ($item) => count($item) === 5);
            // 6 is the only number from len(6) to be guaranteed to NOT INCLUDE both segments from 1
            $foundNumbers[$key][6] = array_values(array_filter($len6, static fn ($item) => count(array_intersect($foundNumbers[$key][1], $item)) === 1))[0];
            // 9 is the only number from len(6) that includes all segments of 4
            $foundNumbers[$key][9] = array_values(array_filter($len6, static fn ($item) => count(array_intersect($foundNumbers[$key][4], $item)) === 4))[0];
            // 0 is the only not found number in len(6)
            $foundNumbers[$key][0] = array_values(array_filter($len6, static fn ($item) => ! in_array($item, [$foundNumbers[$key][6], $foundNumbers[$key][9]])))[0];
            // 5 is the only number from len(5) guaranteed to only be 1 segment off of 6
            $foundNumbers[$key][5] = array_values(array_filter($len5, static fn ($item) => count(array_diff($foundNumbers[$key][6], $item)) === 1))[0];
            // 3 is the only number from len(5) to be guaranteed to INCLUDE both segments from 1
            $foundNumbers[$key][3] = array_values(array_filter($len5, static fn ($item) => count(array_intersect($foundNumbers[$key][1], $item)) === 2))[0];
            // 2 is the only not found number in len(5)
            $foundNumbers[$key][2] = array_values(array_filter($len5, static fn ($item) => ! in_array($item, [$foundNumbers[$key][5], $foundNumbers[$key][3]])))[0];
        }

        $output = array_map(static fn ($item) => trim($item[1]), $input);
        $decodedSegments = [];
        foreach ($output as $key => $line) {
            $sortedSegments = [];
            foreach (explode(" ", $line) as $segments) {
                $segmentArray = str_split($segments);
                sort($segmentArray);
                $sortedSegments[] = array_search($segmentArray, $foundNumbers[$key]);
            }

            $decodedSegments[$key] = (int) implode("", $sortedSegments);
        }


        return new Answer((string) array_sum($decodedSegments));
    }
}