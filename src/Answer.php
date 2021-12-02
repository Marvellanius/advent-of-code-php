<?php
declare(strict_types=1);

namespace marvellanius\Advent;

/**
 * @author Dick van Viegen <dick@tweakers.net>
 */
class Answer
{
    public function __construct(
        private string $answer
    ) {
    }

    public function __toString(): string
    {
        return $this->answer;
    }

    public function equals(Answer $answer): bool
    {
        return $this == $answer;
    }
}