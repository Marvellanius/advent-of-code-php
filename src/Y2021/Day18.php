<?php
declare(strict_types=1);

namespace marvellanius\Advent\Y2021;

use marvellanius\Advent\Answer;
use marvellanius\Advent\AbstractDay;

final class Day18 extends AbstractDay
{
    private ?array $input = null;

    public function run1(): Answer
    {
        if ($this->input) {
            $input = $this->input;
        } else {
            $input = $this->getInputAsArray();
        }
        // add
        // ?reduce
        // explode nested inside 4 => left number to left, right number to right, replace pair with 0
        // split greater than 10 => left/2 floor, right/2 ceil
        $current = "";
        foreach ($input as $line) {
            $current = $this->add($current, trim($line));
            $current = $this->reduce($current);
        }

        $magnitude = $this->calcMagnitude($current);

        return new Answer((string) $magnitude);
    }

    public function run2(): Answer
    {
        if ($this->input) {
            $input = $this->input;
        } else {
            $input = $this->getInputAsArray();
        }

        $maxMagnitude = 0;
        // Loop over each line
        foreach ($input as $first) {
            // Then, per line, loop over each line again to determine magnitudes
            foreach ($input as $second) {
                $magnitude = $this->calcMagnitude($this->reduce($this->add(trim($first), trim($second))));
                $maxMagnitude = max($maxMagnitude, $magnitude);
            }
        }
        return new Answer((string) $maxMagnitude);
    }

    private function add(string $first, string $second): string
    {
        if ($first === "") {
            return $second;
        }
        return "[{$first},{$second}]";
    }

    private function reduce(string $input): string
    {
        return $this->split($this->explode($input));
    }

    private function split(string $string): string
    {
        preg_match_all('/\d{2}/', $string, $doubleDigits, PREG_SET_ORDER, 0);
        if (count($doubleDigits) > 0) {
            // split greater than 10 => left/2 floor, right/2 ceil
            $splitLeft = floor((int) $doubleDigits[0][0] / 2);
            $splitRight = ceil((int) $doubleDigits[0][0] / 2);
            $string = preg_replace("/{$doubleDigits[0][0]}/", "[{$splitLeft},{$splitRight}]", $string, 1);

            return $this->reduce($string);
        }

        return $string;
    }

    private function explode(string $string): string
    {
        $count = 0;
        for ($i = 0; $i < strlen($string); $i++) {
            if ($count === 5) {
                $digits = explode(',', substr($string, $i, strpos($string, "]", $i) - $i));

                $leftDigit = null;
                $lookbehind = substr(strrev(substr($string, 0, $i)), 1);
                preg_match_all('/\d+/', $lookbehind, $leftDigits);
                if (!empty($leftDigits) && !empty($leftDigits[0])) {
                    $leftDigit = $leftDigits[0][0];
                }

                $rightDigit = null;
                $lookahead = substr($string, 1 + strpos($string, "]", $i));
                preg_match_all('/\d+/', $lookahead, $rightDigits);
                if (!empty($rightDigits) && !empty($rightDigits[0])) {
                    $rightDigit = $rightDigits[0][0];
                }

                if ($rightDigit !== null) {
                    $newRightDigit = (int) $digits[1] + (int) $rightDigit;
                    $lookahead = preg_replace("/{$rightDigit}/", (string) $newRightDigit, $lookahead, 1);
                }

                if ($leftDigit !== null) {
                    $newLeftDigit = (int) $digits[0] + (int) strrev($leftDigit);
                    $lookbehind = strrev(preg_replace("/{$leftDigit}/", strrev((string) $newLeftDigit), $lookbehind, 1));
                }

                $string = "{$lookbehind}0{$lookahead}";

                return $this->explode($string);
            }
            switch ($string[$i]) {
                case '[':
                    $count++;
                    break;
                case ']':
                    $count--;
                    break;
            }
        }
        return $string;
    }

    private function calcMagnitude(string $string): int
    {
        preg_match_all("/\[\d+,\d+\]/", $string, $pairs, PREG_SET_ORDER, 0);
        if (!empty($pairs)) {
            $digits = explode(",", str_replace(["[", "]"], "", $pairs[0][0]));
            $digit = 3 * (int) $digits[0] + 2 * (int) $digits[1];
            $string = preg_replace("/" . str_replace(["[", "]"], ["\[", "\]"], $pairs[0][0]) . "/", (string) $digit, $string, 1);
            return $this->calcMagnitude($string);
        }
        return (int)$string;
    }

    public function setInputArray(array $input): void
    {
        $this->input = $input;
    }
}