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
        $input = array_map(static fn ($item) => explode(" ", trim($item[1])) , $this->getInputAsArray(true, "|"));

        $segmentLetters = [];
        foreach ($input as $digit) {
            foreach ($digit as $segment) {
                switch(strlen($segment)) {
                    case 2:
                        $segmentLetters[1] = $segment;
                        break;
                    case 3:
                        $segmentLetters[7] = $segment;
                        break;
                    case 4:
                        $segmentLetters[4] = $segment;
                        break;
                    case 5:
                        [$segmentLetters[2], $segmentLetters[3], $segmentLetters[5]] = $this->setSegmentsForLength(5);
                        break;
                    case 6:
                        [$segmentLetters[6], $segmentLetters[9]] = $this->setSegmentsForLength(6);
                        break;
                    case 7:
                        $segmentLetters[8] = $segment;
                        break;
                }
                if (in_array(strlen($segment), [2,3,4,7])) {
                    $count++;
                }
            }
        }

        return new Answer((string) 20);
    }

}