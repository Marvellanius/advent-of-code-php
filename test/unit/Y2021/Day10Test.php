<?php
declare(strict_types=1);

namespace marvellanius\Advent\Y2021;

use marvellanius\Advent\AbstractDay;
use marvellanius\Advent\Answer;
use marvellanius\Advent\DayTestCase;

/**
 * @author Dick van Viegen <dick@tweakers.net>
 */
class Day10Test extends DayTestCase
{

    protected function getDay(): AbstractDay
    {
        $day = new Day10();
        $day->setInput(dirname(__DIR__, 3) . "/resources/Y2021/Day10-test.txt");
        return $day;
    }

    protected function getExpectedAnswerForAssignment(int $assignment): Answer
    {
        return match($assignment) {
            1 => new Answer("26397"),
            2 => new Answer("test answer not yet found")
        };
    }
}