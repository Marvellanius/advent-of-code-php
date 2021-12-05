<?php
declare(strict_types=1);

namespace marvellanius\Advent\Y2021;

use marvellanius\Advent\Answer;
use marvellanius\Advent\AbstractDay;
use marvellanius\Advent\DayTestCase;

final class Day5Test extends DayTestCase
{
    protected function getDay(): AbstractDay
    {
        $day = new Day5();
        $day->setInput(dirname(__DIR__, 3) . "/resources/Y2021/Day5-test.txt");
        return $day;
    }

    protected function getExpectedAnswerForAssignment(int $assignment): Answer
    {
        return match($assignment) {
            1 => new Answer('5'),
            2 => new Answer('12')
        };
    }
}