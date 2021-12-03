<?php

namespace marvellanius\Advent\Y2021;

use marvellanius\Advent\Answer;
use marvellanius\Advent\AbstractDay;
use marvellanius\Advent\DayTestCase;

final class Day1Test extends DayTestCase
{
    protected function getDay(): AbstractDay
    {
        $day = new Day1();
        $day->setInput(dirname(__DIR__, 3) . "/resources/Y2021/Day1-test.txt");
        return $day;
    }

    protected function getExpectedAnswerForAssignment(int $assignment): Answer
    {
        return match($assignment) {
            1 => new Answer(7),
            2 => new Answer(5)
        };
    }
}