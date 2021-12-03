<?php
declare(strict_types=1);

namespace marvellanius\Advent;

use PHPUnit\Framework\TestCase;

/**
 * @author Dick van Viegen <dick@tweakers.net>
 */
abstract class DayTestCase extends TestCase
{
    /** @test */
    public function it_should_answer_assignment_1_correctly(): void
    {
        $day = $this->getDay();
        $expectedAnswer = $this->getExpectedAnswerForAssignment(1);

        $actualAnswer = $day->run1();

        self::assertTrue($actualAnswer->equals($expectedAnswer));
    }

    /** @test */
    public function it_should_answer_assignment_2_correctly(): void
    {
        $day = $this->getDay();
        $expectedAnswer = $this->getExpectedAnswerForAssignment(2);

        $actualAnswer = $day->run2();

        self::assertTrue($actualAnswer->equals($expectedAnswer));

    }

    abstract protected function getDay(): AbstractDay;
    abstract protected function getExpectedAnswerForAssignment(int $assignment): Answer;
}