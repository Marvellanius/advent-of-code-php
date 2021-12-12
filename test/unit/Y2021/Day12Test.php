<?php
declare(strict_types=1);

namespace marvellanius\Advent\Y2021;

use marvellanius\Advent\Answer;
use marvellanius\Advent\AbstractDay;
use marvellanius\Advent\DayTestCase;

final class Day12Test extends DayTestCase
{
    protected function getDay(): AbstractDay
    {
        $day = new Day12();
        $day->setInput(dirname(__DIR__, 3) . "/resources/Y2021/Day12-test.txt");
        return $day;
    }

    protected function getExpectedAnswerForAssignment(int $assignment): Answer
    {
        return match($assignment) {
            1 => new Answer("226"),
            2 => new Answer("Get the expected answer for assignment 2 from https://adventofcode.com")
        };
    }
}