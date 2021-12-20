<?php
declare(strict_types=1);

namespace marvellanius\Advent\Y2021;

use marvellanius\Advent\Answer;
use marvellanius\Advent\AbstractDay;
use marvellanius\Advent\DayTestCase;

final class Day18Test extends DayTestCase
{
    protected function getDay(): AbstractDay
    {
        $day = new Day18();
        $day->setInput(dirname(__DIR__, 3) . "/resources/Y2021/Day18-test.txt");
        return $day;
    }

    protected function getExpectedAnswerForAssignment(int $assignment): Answer
    {
        return match($assignment) {
            1 => new Answer("4140"),
            2 => new Answer("Get the expected answer for assignment 2 from https://adventofcode.com")
        };
    }

    /** @dataProvider magnitudeDataProvider */
    public function test_it_should_calculate_magnitude($input, $expectedAnswer): void
    {
        $day = new Day18();
        $day->setInputArray($input);

        $actualAnswer = $day->run1();

        self::assertSame($expectedAnswer, $actualAnswer->__toString());
    }

    public function magnitudeDataProvider(): array
    {
        return [
            [['[[1,2],[[3,4],5]]'], '143'],
            [['[[[[0,7],4],[[7,8],[6,0]]],[8,1]]'], '1384'],
            [['[[[[1,1],[2,2]],[3,3]],[4,4]]'], '445'],
            [['[[[[3,0],[5,3]],[4,4]],[5,5]]'], '791'],
            [['[[[[5,0],[7,4]],[5,5]],[6,6]]'], '1137'],
            [['[[[[8,7],[7,7]],[[8,6],[7,7]]],[[[0,7],[6,6]],[8,7]]]'], '3488'],
        ];
    }
}