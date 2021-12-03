<?php
declare(strict_types=1);

namespace marvellanius\Advent\Y2021;

use marvellanius\Advent\Answer;
use marvellanius\Advent\AbstractDay;

/**
 * @author Dick van Viegen <dick@tweakers.net>
 */
class Day2 extends AbstractDay
{
    private int $aim = 0;
    private int $depth = 0;
    private int $horizontalPosition = 0;

    public function run1(): Answer
    {
        $array = array_map(static fn ($item) => preg_split("/[\s]/", rtrim($item)), $this->getInputAsArray());
        $horizontalPosition = array_reduce(
            array_filter($array, static fn ($item) => $item[0] === "forward"),
            static fn ($carry, $item) => $carry += (int) $item[1]
        );

        $depth = array_reduce(
            array_filter($array, static fn ($item) => in_array($item[0], ["up", "down"])),
            static function ($carry, $item) {

                return match (true) {
                    $item[0] === "up" => $carry -= (int) $item[1],
                    $item[0] === "down" => $carry += (int) $item[1],
                };
            }
        );

        return new Answer((string) ($depth * $horizontalPosition));
    }

    public function run2(): Answer
    {
        $array = array_map(static fn ($item) => preg_split("/[\s]/", rtrim($item)), $this->getInputAsArray());

        foreach ($array as $instruction) {
            match ($instruction[0]) {
                "up" => $this->moveUp($instruction[1]),
                "down" => $this->moveDown($instruction[1]),
                "forward" => $this->moveForward($instruction[1]),
                default => null,
            };
        }

        return new Answer((string) ($this->depth * $this->horizontalPosition));
    }

    private function moveForward($amount): void
    {
        // add $amount to horizontal position
        $this->horizontalPosition += $amount;
        // increase $depth by $aim * $amount
        $this->depth += ($this->aim * $amount);
    }

    private function moveUp($amount): void
    {
        $this->aim -= $amount;
    }

    private function moveDown($amount): void
    {
        $this->aim += $amount;
    }
}