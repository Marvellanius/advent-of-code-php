<?php
declare(strict_types=1);

namespace marvellanius\Advent\Y2021;

use marvellanius\Advent\Answer;
use marvellanius\Advent\AbstractDay;

final class Day13 extends AbstractDay
{
    public function run1(): Answer
    {
        [$paper, $folds] = $this->generatePaperAndFolds();

        $paper = $this->foldPaper($paper, $folds, 1);
        $count = count($paper);

        return new Answer((string) $count);
    }

    public function run2(): Answer
    {
        [$paper, $folds] = $this->generatePaperAndFolds();

        $paper = $this->foldPaper($paper, $folds);

        $x = array_column($paper, 0);
        $y = array_column($paper, 1);

        $matrix = array_fill(0, max($y) + 1, array_fill(0, max($x) + 1, '.'));

        foreach ($paper as $coordinates) {
            $matrix[$coordinates[1]][$coordinates[0]] = '#';
        }

        $output = '';
        foreach ($matrix as $line) {
            $output .= (implode('', $line) . "\r\n");
        }

        return new Answer("\r\n" . $output);
    }

    private function generatePaperAndFolds(): array
    {
        $input = array_map(static fn ($item) => trim($item), $this->getInputAsArray());
        $paper = [];

        foreach ($input as $line) {
            if ($line === "") {
                break;
            }
            $coords = explode(',', $line);
            $paper[] = [(int) $coords[0], (int) $coords[1]];
        }

        $folds = array_filter($input, static fn ($item) => str_contains($item, 'fold'));
        $folds = array_values(array_map(static fn ($item) => explode('=', str_replace('fold along ', '', $item)), $folds));

        return [$paper, $folds];
    }

    private function foldPaper(array $paper, array $folds, int $limit = null): array
    {
        $step = 0;
        foreach ($folds as $fold) {
            $step++;
            if ($fold[0] === 'x') {
                $itemKey = 0;
            } else {
                $itemKey = 1;
            }
            $belowFold = [];
            $aboveFold = [];

            foreach ($paper as $line) {
                if ($line[$itemKey] > (int) $fold[1]) {
                    $belowFold[] = $line;
                } else if ($line[$itemKey] < (int) $fold[1]) {
                    $aboveFold[] = $line;
                }
            }

            foreach ($belowFold as $row_key => $line) {
                $newCoord = (int) $fold[1] - ($line[$itemKey] - (int) $fold[1]);
                $belowFold[$row_key][$itemKey] = $newCoord;

                if (in_array($belowFold[$row_key], $aboveFold)) {
                    unset($belowFold[$row_key]);
                }
            }
            $paper = [];
            foreach ([...$aboveFold, ...$belowFold] as $line) {
                if (! in_array($line, $paper)) {
                    $paper[] = $line;
                }
            }

            if ($limit && $step === $limit) {
                break;
            }
        }

        return $paper;
    }
}