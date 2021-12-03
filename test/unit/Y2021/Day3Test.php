<?php

namespace marvellanius\Advent\Y2021;

use marvellanius\Advent\Answer;
use marvellanius\Advent\AbstractDay;
use marvellanius\Advent\DayTestCase;

final class Day3Test extends DayTestCase
{
    protected function getDay(): AbstractDay
    {
        $day = new Day3();
        $day->setInput(dirname(__DIR__) . "/../../resources/Y2021/Day3-test.txt");
        return $day;
    }

    protected function getExpectedAnswerForAssignment(int $assignment): Answer
    {
        return match($assignment) {
            1 => new Answer(198),
            2 => new Answer(230)
        };
    }
}