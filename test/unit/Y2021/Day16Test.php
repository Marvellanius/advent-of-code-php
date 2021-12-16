<?php
declare(strict_types=1);

namespace marvellanius\Advent\Y2021;

use marvellanius\Advent\Answer;
use marvellanius\Advent\AbstractDay;
use marvellanius\Advent\DayTestCase;

final class Day16Test extends DayTestCase
{
    protected function getDay(): AbstractDay
    {
        $day = new Day16();
        $day->setInput(dirname(__DIR__, 3) . "/resources/Y2021/Day16-test.txt");
        return $day;
    }

    protected function getExpectedAnswerForAssignment(int $assignment): Answer
    {
        return match($assignment) {
            1 => new Answer("16"),
            2 => new Answer("Get the expected answer for assignment 2 from https://adventofcode.com")
        };
    }

    /** @dataProvider versionSumDataProvider */
    public function test_it_should_get_correctly_sum_versions($input, $expectedAnswer): void
    {
        $day = new Day16();
        $day->setInputString($input);

        $actualAnswer = $day->run1();

        self::assertSame($expectedAnswer, $actualAnswer->__toString());
    }

    public function versionSumDataProvider(): array
    {
        return [
            ['8A004A801A8002F478', '16'],
            ['620080001611562C8802118E34', '12'],
            ['C0015000016115A2E0802F182340', '23'],
            ['A0016C880162017C3686B18A3D4780', '31'],
        ];
    }

    /** @dataProvider packageValueDataProvider */
    public function test_it_should_get_correctly_calculate_values($input, $expectedAnswer): void
    {
        $day = new Day16();
        $day->setInputString($input);

        $actualAnswer = $day->run2();

        self::assertSame($expectedAnswer, $actualAnswer->__toString());
    }

    public function packageValueDataProvider(): array
    {
        return [
            ['C200B40A82', '3'],
            ['04005AC33890', '54'],
            ['880086C3E88112', '7'],
            ['CE00C43D881120', '9'],
            ['D8005AC2A8F0', '1'],
            ['F600BC2D8F', '0'],
            ['9C005AC2F8F0', '0'],
            ['9C0141080250320F1802104A08', '1'],
        ];
    }
}