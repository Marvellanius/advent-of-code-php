<?php

namespace marvellanius\Advent\Y2021;

use marvellanius\Advent\Answer;
use marvellanius\Advent\AbstractDay;
use marvellanius\Advent\DayTestCase;

final class Day2Test extends DayTestCase
{
    protected function getDay(): AbstractDay
    {
        $day = new Day2();
        $day->setInput(dirname(__DIR__) . "/../../resources/Y2021/Day2-test.txt");
        return $day;
    }

    protected function getExpectedAnswerForAssignment(int $assignment): Answer
    {
        return match($assignment) {
            1 => new Answer(150),
            2 => new Answer(900)
        };
    }
}