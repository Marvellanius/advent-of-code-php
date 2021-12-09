<?php
declare(strict_types=1);

namespace marvellanius\Advent\Y2021;

use marvellanius\Advent\Answer;
use marvellanius\Advent\AbstractDay;
use marvellanius\Advent\DayTestCase;

final class Day9Test extends DayTestCase
{
    protected function getDay(): AbstractDay
    {
        $day = new Day9();
        $day->setInput(dirname(__DIR__, 3) . "/resources/Y2021/Day9-test.txt");
        return $day;
    }

    protected function getExpectedAnswerForAssignment(int $assignment): Answer
    {
        return match($assignment) {
            1 => new Answer("15"),
            2 => new Answer("1134")
        };
    }
}