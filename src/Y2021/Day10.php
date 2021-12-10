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

        $invalidLines = [];

        foreach ($input as $key => $symbols) {
            $expectedClosingBrackets = [];
            foreach ($symbols as $symbol) {
                $currentSymbolKey = array_search($symbol, $openingSymbols);
                if ($currentSymbolKey !== false) {
                    $expectedClosingBrackets[] = $closingSymbols[$currentSymbolKey];
                } else if (in_array($symbol, $closingSymbols) && $symbol !== array_pop($expectedClosingBrackets)) {
                    $invalidLines[$key] = $input[$key];
                    $illegalCharacters[$key][] = $symbol;
                    break;
                }
            }
        }

        $score = 0;
        $scoreMap = [
            ')' => 3,
            ']' => 57,
            '}' => 1197,
            '>' => 25137
        ];

        foreach ($illegalCharacters as $illegalCharacter) {
            $score += $scoreMap[$illegalCharacter[0]];
        }

        return new Answer((string) $score);
    }

    public function run2(): Answer
    {
        $input = array_map(static fn ($item) => str_split(trim($item)), $this->getInputAsArray());
        $openingSymbols = ['(','[','{','<'];
        $closingSymbols = [')',']','}','>'];

        $invalidLines = [];

        foreach ($input as $key => $line) {
            $expectedClosingBrackets = [];
            $bracketsToFill = [];
            foreach ($line as $symbol) {
                $currentSymbolKey = array_search($symbol, $openingSymbols);
                if ($currentSymbolKey !== false) {
                    $expectedClosingBrackets[] = $closingSymbols[$currentSymbolKey];
                    $bracketsToFill = [$closingSymbols[$currentSymbolKey], ...$bracketsToFill];
                } else if (in_array($symbol, $closingSymbols) && $symbol !== array_pop($expectedClosingBrackets)) {
                    $invalidLines[$key] = $line;
                    $illegalCharacters[$key][] = $symbol;
                    break;
                } else if (in_array($symbol, $closingSymbols) && $symbol === reset($bracketsToFill)) {
                    $bracketsToFill = array_slice($bracketsToFill, 1);
                }
            }
            if (!in_array($line, $invalidLines)) {
                $toAddLines[$key] = $bracketsToFill;
            }
        }

        $incomplete = array_filter($input, static fn ($item) => ! in_array($item, $invalidLines));

        $scoreMap = [
            ')' => 1,
            ']' => 2,
            '}' => 3,
            '>' => 4
        ];
        $lineScores = [];
        foreach ($incomplete as $key => $line) {
            $score = 0;

            foreach ($toAddLines[$key] as $symbol) {
                $score *= 5;
                $score += $scoreMap[$symbol];
            }
            $lineScores[] = $score;
        }

        sort($lineScores);

        return new Answer((string) $lineScores[floor(count($lineScores)/2)]);
    }
}