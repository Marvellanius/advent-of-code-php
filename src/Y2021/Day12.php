<?php
declare(strict_types=1);

namespace marvellanius\Advent\Y2021;

use marvellanius\Advent\Answer;
use marvellanius\Advent\AbstractDay;

final class Day12 extends AbstractDay
{
    public function run1(): Answer
    {
        $caves = $this->generateCaveMap();

        $paths = [];
        $this->findPaths($caves, 'start', 'end', $paths, []);

        return new Answer((string) count($paths));
    }

    public function run2(): Answer
    {
        $caves = $this->generateCaveMap();

        $paths = [];
        $this->findPaths($caves, 'start', 'end', $paths, [], true);

        return new Answer((string) count($paths));
    }

    private function generateCaveMap(): array
    {
        $input = array_map(static fn ($item) => trim($item), $this->getInputAsArray());

        $caves = [];
        foreach ($input as $connection) {
            $connArray = explode('-', $connection);
            $caves[$connArray[0]][] = $connArray[1];
            $caves[$connArray[1]][] = $connArray[0];
        }

        return $caves;
    }

    private function findPaths(array $caves, string $origin, string $destination, array &$paths, array $curr_path, bool $lc2 = false): void
    {
        if ($origin === $destination) {
            $curr_path[] = $origin;
            $paths[] = $curr_path;
            return;
        }

        if (ctype_lower($origin)) {
            if ($lc2) {
                if (in_array($origin, $curr_path, true)) {
                    if ($origin === "start") {
                        return;
                    }
                    $lowercase = array_count_values(array_filter($curr_path, static fn ($item) => ctype_lower($item)));
                    if (in_array(2, $lowercase, true)) {
                        return;
                    }
                }
            } else {
                if (in_array($origin, $curr_path, true)) {
                    return;
                }
            }
        }

        $curr_path[] = $origin;
        foreach ($caves[$origin] as $neighbour) {
            $this->findPaths($caves, $neighbour, $destination, $paths, $curr_path, $lc2);
        }
    }
}