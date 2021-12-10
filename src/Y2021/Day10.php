<?php
declare(strict_types=1);

namespace marvellanius\Advent\Y2021;

use marvellanius\Advent\AbstractDay;
use marvellanius\Advent\Answer;

/**
 * @author Dick van Viegen <dick@tweakers.net>
 */
class Day10 extends AbstractDay
{

    public function run1(): Answer
    {
        $input = array_map(static fn ($item) => str_split(trim($item)), $this->getInputAsArray());
        $openingSymbols = ['(','[','{','<'];
        $closingSymbols = [')',']','}','>'];

        $expectedClosingBrackets = [];
        $invalidLines = [];
        $illegalCharacters = [
            ')' => 0,
            ']' => 0,
            '}' => 0,
            '>' => 0,
        ];

        foreach ($input as $key => $symbols) {
            foreach ($symbols as $symbol) {
                $currentSymbolKey = array_search($symbol, $openingSymbols);
                if ($currentSymbolKey) {
                    $expectedClosingBrackets = [$closingSymbols[$currentSymbolKey], ...$expectedClosingBrackets];
                } else if (in_array($symbol, $closingSymbols) && $symbol !== $expectedClosingBrackets[0]) {
                    $invalidLines[$key] = $input[$key];
                    $illegalCharacters[$key][] = $symbol;
                    print_r("Found {$symbol}, expected {$expectedClosingBrackets[0]} \r\n");
                }
            }
        }
        die();

        return new Answer((string) $score);
    }

    public function run2(): Answer
    {
        $input = array_map(static fn ($item) => str_split(rtrim($item)), $this->getInputAsArray());

        return new Answer("not implemented");
    }
}