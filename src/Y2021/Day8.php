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
        $input = array_map(static fn ($item) => trim(explode("|", $item)[1]), $this->getInputAsArray());
        $input = array_map('str_split', explode(" ",implode(" ", $input)));
        $uniqueSequences = [];
        for ($i = 0; $i < count($input); $i++) {
            sort($input[$i]);
        }

        $input = array_map(static fn ($item) => implode($item), $input);
        $uniqueSequences = array_values(array_unique($input));

        for ($i = 0; $i < count($uniqueSequences); $i++) {
            switch (strlen($uniqueSequences[$i])) {
                case 2:
                    $foundNumbers[1] = $uniqueSequences[$i];
                    break;
                case 3:
                    $foundNumbers[7] = $uniqueSequences[$i];
                    break;
                case 4:
                    $foundNumbers[4] = $uniqueSequences[$i];
                    break;
                case 7:
                    $foundNumbers[8] = $uniqueSequences[$i];
                    break;
            }
        }
        $length5 = array_values(array_filter($uniqueSequences, static fn ($item) => strlen($item) === 5));
        $length6 = array_values(array_filter($uniqueSequences, static fn ($item) => strlen($item) === 6));

        for ($i = 0; $i < count($length6); $i++) {
            if (str_contains($length6[$i],$foundNumbers[1])) {
                $foundNumbers[9] = $length6[$i];
            } else {
                $foundNumbers[6] = $length6[$i];
            }
        }

        foreach ($length5 as $segment) {
            match (true) {
                str_contains($segment, $foundNumbers[1]) => $foundNumbers[3] = $segment,
                count(array_diff(str_split($foundNumbers[6]), str_split($segment))) === 1 => $foundNumbers[5] = $segment,
                default => $foundNumbers[2] = $segment,
            };
        }

        var_dump($foundNumbers);
        die();
        $input = array_map(static fn ($item) => explode(" ", trim($item[1])) , $input);
        $input = array_map(static fn ($item) => str_split($item), $input);
        $numbers = $this->deduceSegments($input);
        $segmentLetters = [];
//        $count = 0;
//        foreach ($input as $digit) {
//            foreach ($digit as $segment) {
//                switch(strlen($segment)) {
//                    case 2:
//                        $segmentLetters[1] = $segment;
//                        break;
//                    case 3:
//                        $segmentLetters[7] = $segment;
//                        break;
//                    case 4:
//                        $segmentLetters[4] = $segment;
//                        break;
//                    case 5:
//                        [$segmentLetters[2], $segmentLetters[3], $segmentLetters[5]] = $this->setSegmentsForLength(5);
//                        break;
//                    case 6:
//                        [$segmentLetters[6], $segmentLetters[9]] = $this->setSegmentsForLength(6);
//                        break;
//                    case 7:
//                        $segmentLetters[8] = $segment;
//                        break;
//                }
//                if (in_array(strlen($segment), [2,3,4,7])) {
//                    $count++;
//                }
//            }
//        }

        return new Answer((string) 20);
    }

    private function deduceSegments(array $input): array
    {
        $numbers = $input;
//        for ($i = 0; $i < count($input); $i++) {
//        }

        var_dump($input);
        return $numbers;
    }

}