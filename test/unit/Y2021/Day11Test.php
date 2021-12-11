<?php
declare(strict_types=1);

namespace marvellanius\Advent\Y2021;

use marvellanius\Advent\AbstractDay;
use marvellanius\Advent\Answer;
use marvellanius\Advent\DayTestCase;

/**
 * @author Dick van Viegen <dick@tweakers.net>
 */
class Day11Test extends DayTestCase
{

    protected function getDay(): AbstractDay
    {
        $day = new Day11();
        $day->setInput(dirname(__DIR__, 3) . "/resources/Y2021/Day11-test.txt");
        return $day;
    }

    protected function getExpectedAnswerForAssignment(int $assignment): Answer
    {
        return match($assignment) {
            1 => new Answer("1656"),
            2 => new Answer("195")
        };
    }
}