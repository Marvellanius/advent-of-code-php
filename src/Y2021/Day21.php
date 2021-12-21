<?php
declare(strict_types=1);

namespace marvellanius\Advent\Y2021;

use marvellanius\Advent\Answer;
use marvellanius\Advent\AbstractDay;
use marvellanius\Advent\Y2021\Day21\Dirac;
use marvellanius\Advent\Y2021\Day21\Universe;

final class Day21 extends AbstractDay
{
    private int $deterministicDieResult = 0;
    private array $knownOutcomes = [];

    public function run1(): Answer
    {
        $input = $this->getInputAsArray();
        $positions = [];
        foreach ($input as $line) {
            preg_match_all('/\d+/', trim($line), $position);
            $positions[] = (int) $position[0][1];
        }

        $naive = $this->firstTo1000($positions);
        $clean = $this->firstTo1000CleanedUp($positions);

        return new Answer("Naive implementation result: {$naive}; Cleaned up result: {$clean} \r\n");
    }

    public function run2(): Answer
    {
        $input = $this->getInputAsArray();
        $positions = [];
        foreach ($input as $line) {
            preg_match_all('/\d+/', trim($line), $position);
            // get positions, reduce them by 1, as this makes modulo operations easier - DON'T FORGET TO INCREASE BY 1 WHEN REGISTERING SCORES
            $positions[] = (int) $position[0][1] - 1;
        }

        // should just be able to get the max() of the resulting answer array
        $answer = max($this->determineWinners($positions[0], $positions[1], 0, 0));

        return new Answer((string) $answer);
    }

    public function firstTo1000(array $positions): int
    {
        $die = [100, ...range(1, 99)];
        $board = [10, ...range(1, 9)];
        $losingPlayerScore = 0;
        $diceThrows = 0;
        $maxScore = 0;

        $positionPlayer1 = $positions[0];
        $positionPlayer2 = $positions[1];
        $scorePlayer1 = 0;
        $scorePlayer2 = 0;
        $i = 1;
        $turn = true;
        while ($maxScore < 1000) {
//            echo "After {$diceThrows} throws, scores are Player 1: {$scorePlayer1}, Player 2: {$scorePlayer2} \r\n";

            $throwScore = $die[$i % 100] + $die[($i+1) % 100] + $die[($i+2) % 100];

            if ($turn) {
                $positionPlayer1 += $throwScore;
                $scorePlayer1 += $board[$positionPlayer1 % 10];
                $turn = false;
            } else {
                $positionPlayer2 += $throwScore;
                $scorePlayer2 += $board[$positionPlayer2 % 10];
                $turn = true;
            }
            $maxScore = max($scorePlayer1, $scorePlayer2);
            $losingPlayerScore = min($scorePlayer1, $scorePlayer2);

            $diceThrows += 3;
            $i += 3;
        }

        return $losingPlayerScore * $diceThrows;
    }

    public function firstTo1000CleanedUp(array $positions): int
    {
        $playerOneScore = 0;
        $playerTwoScore = 0;
        // again, decrease positions by 1, so as to make modulo use easier
        $playerOnePosition = $positions[0] - 1;
        $playerTwoPosition = $positions[1] - 1;

        while (true) {
            echo "After {$this->deterministicDieResult} throws, scores are Player 1: {$playerOneScore}, Player 2: {$playerTwoScore} \r\n";

            $throwOne = 0;
            foreach ($this->rollDeterministicDie(3) as $result) {
                $throwOne += $result;
            }

            $playerOnePosition = ($playerOnePosition + $throwOne) % 10;
            $playerOneScore += $playerOnePosition + 1;
            if ($playerOneScore >= 1000) {
                return $playerTwoScore * $this->deterministicDieResult;
            }

            $throwTwo = 0;
            foreach ($this->rollDeterministicDie(3) as $result) {
                $throwTwo += $result;
            }

            $playerTwoPosition = ($playerTwoPosition + $throwTwo) % 10;
            $playerTwoScore += $playerTwoPosition + 1;
            if ($playerTwoScore >= 1000) {
                return $playerOneScore * $this->deterministicDieResult;
            }
        }
    }

    private function rollDeterministicDie(int $times): Iterable
    {
        for ($i = 1; $i <= $times; $i++) {
            yield ++$this->deterministicDieResult;
        }
    }

    private function determineWinners($firstPosition, $secondPosition, $firstScore, $secondScore): array
    {
        // if given scores include a 21, return a win for the appropriate player
        if ($firstScore >= 21) {
            return [1, 0];
        }
        if ($secondScore >= 21) {
            return [0, 1];
        }
        // if this set of arguments appears in our knownOutcomes array (is memoized), return the recorded outcome
        $outcomeKey = implode(',', [$firstPosition, $secondPosition, $firstScore, $secondScore]);
        if (array_key_exists($outcomeKey, $this->knownOutcomes)) {
            return $this->knownOutcomes[$outcomeKey];
        }

        // purely based on current player positions and scores, we should be able to determine a winner
        $answer = [0, 0];
        foreach (range(1, 3) as $roll1) {
            foreach (range(1, 3) as $roll2) {
                foreach (range(1, 3) as $roll3) {
                    $pos_1_new = ($firstPosition + $roll1 + $roll2 + $roll3) % 10;
                    // Recover the decreased 1 at input readout for easier modulo workings
                    $s1_new = 1 + $firstScore + $pos_1_new;

                    // Now call method recursively, swapping player inputs around so as to simulate alternating turns
                    [$win1, $win2] = $this->determineWinners($secondPosition, $pos_1_new, $secondScore, $s1_new);
                    // Don't forget to swap the results before summing to the current answer, as inputs were swapped.
                    $answer = [$answer[0] + $win2, $answer[1] + $win1];
                }
            }
        }
        $this->knownOutcomes[$outcomeKey] = $answer;
        return $answer;
    }
}
