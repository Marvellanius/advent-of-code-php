<?php
declare(strict_types=1);

namespace marvellanius\Advent\Y2021;

use marvellanius\Advent\Answer;
use marvellanius\Advent\AbstractDay;

final class Day4 extends AbstractDay
{
    public function run1(): Answer
    {
        [$numbersToMark, $bingoCards] = $this->setupGame();

        $markedNumbers = [];
        foreach ($numbersToMark as $mark) {
            $markedNumbers[] = $mark;
            $winner = $this->determineFirstWinner($bingoCards, $markedNumbers);
            if ($winner) {
                break;
            }
        }

        return new Answer((string) $this->determineScore($winner, $markedNumbers));
    }

    public function run2(): Answer
    {
        [$numbersToMark, $bingoCards] = $this->setupGame();

        $markedNumbers = [];
        foreach ($numbersToMark as $mark) {
            $markedNumbers[] = $mark;
            if (count($bingoCards) > 1) {
                $this->determineLastWinner($bingoCards, $markedNumbers);
            } else {
                break;
            }
        }

        $winner = array_values($bingoCards)[0];

        return new Answer((string) $this->determineScore($winner, $markedNumbers));
    }

    private function setupGame(): array
    {
        $input = array_map(static fn ($value) => trim($value), $this->getInputAsArray());
        $numbersToMark = explode(",", $input[0]);
        $count = 0;
        $bingoCards = [];

        foreach (array_slice($input, 1) as $line) {
            if ($line === "") {
                $count++;
                continue;
            }
            $bingoCards[$count]['rows'][] = preg_split("/[\s]+/", $line);
        }

        foreach ($bingoCards as $key => $bingoCard) {
            foreach ($bingoCard['rows'] as $row) {
                foreach ($row as $k => $value) {
                    $bingoCards[$key]['columns'][$k][] = $value;
                    $bingoCards[$key]['flat'][] = $value;
                }
            }
        }

        return [$numbersToMark, $bingoCards];
    }

    private function determineScore(array $winner, array $markedNumbers): int
    {
        $unmarked = array_diff($winner['flat'], $markedNumbers);

        $sumUnmarked = array_reduce(
            $unmarked,
            static fn ($carry, $item) => $carry += $item
        );

        return end($markedNumbers) * $sumUnmarked;
    }

    private function determineFirstWinner(array $bingoCards, array $markedNumbers): ?array
    {
        foreach ($bingoCards as $bingoCard) {
            foreach ($bingoCard["rows"] as $row) {
                if (array_intersect($row, $markedNumbers) === $row) {
                    return $bingoCard;
                }
            }
            foreach ($bingoCard["columns"] as $column) {
                if (array_intersect($column, $markedNumbers) === $column) {
                    return $bingoCard;
                }
            }
        }
        return null;
    }

    private function determineLastWinner(array &$bingoCards, array $markedNumbers): void
    {
        foreach ($bingoCards as $key => $bingoCard) {
            foreach ($bingoCard["rows"] as $row) {
                if (array_intersect($row, $markedNumbers) === $row) {
                    unset($bingoCards[$key]);
                    break;
                }
            }
            foreach ($bingoCard["columns"] as $column) {
                if (array_intersect($column, $markedNumbers) === $column) {
                    unset($bingoCards[$key]);
                    break;
                }
            }
        }
    }
}