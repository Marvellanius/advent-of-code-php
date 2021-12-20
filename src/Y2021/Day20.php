<?php
declare(strict_types=1);

namespace marvellanius\Advent\Y2021;

use marvellanius\Advent\Answer;
use marvellanius\Advent\AbstractDay;

final class Day20 extends AbstractDay
{
    public function run1(): Answer
    {
        [$image, $algorithm] = $this->parsePuzzleInput();

        foreach (range(1, 2) as $generation) {
            $image = $this->enhance($image, $algorithm, $generation);
        }

        return new Answer((string) $this->countActivePixels($image));
    }

    public function run2(): Answer
    {
        [$image, $algorithm] = $this->parsePuzzleInput();

        foreach (range(1, 50) as $generation) {
            $image = $this->enhance($image, $algorithm, $generation);
        }

        return new Answer((string) $this->countActivePixels($image));
    }

    private function parsePuzzleInput(): array
    {
        $input = $this->getInputAsArray();
        $algorithm = trim($input[0]);

        $image = array_map(static fn ($line) => str_split(trim($line)), array_slice($input, 2));

        return [$image, $algorithm];
    }

    private function enhance(array $image, string $algorithm, int $generation): array
    {
        if ($generation === 1 || $generation % 2) {
            $image = $this->padImage($image);
        }

        $c_bound = count($image[0]);
        $r_bound = count(array_column($image, 0));
        $directions = [[-1, -1], [-1, 0], [-1, 1], [0, -1], [0, 0], [0, 1], [1, -1],  [1, 0], [1, 1]];


        $enhancedImage = $image;
        foreach ($image as $lkey => $line) {

            foreach ($line as $pkey => $pixel) {
                $pixelEnhance = [];
                foreach ($directions as $direction) {
                    $c_i = $pkey + $direction[1];
                    $r_i = $lkey + $direction[0];
                    if ($r_i < 0 || $c_i < 0 || $r_i >= $r_bound || $c_i >= $c_bound) {
                        $pixelEnhance[] = $generation % 2 ? '.' : '#';
                        continue;
                    }
                    $pixelEnhance[] = $image[$r_i][$c_i];
                }
                $pixelBinary = str_replace(['.', '#'], ['0', '1'], implode('', $pixelEnhance));
                $pixelValue = bindec($pixelBinary);

                $newPixel = $algorithm[$pixelValue];
                $enhancedImage[$lkey][$pkey] = $newPixel;
            }
        }
        return $enhancedImage;
    }

    private function padImage(array $image): array
    {
        $emptyRows = array_fill(0, 5, array_fill(0, count($image[0]), '.'));
        $paddedImage = [...$emptyRows, ...$image, ...$emptyRows];
        foreach ($paddedImage as $lkey => $line) {
            $padding = array_fill(0, 5, '.');
            $paddedImage[$lkey] = [...$padding, ...$line, ...$padding];
        }

        return $paddedImage;
    }

    private function countActivePixels(array $image): int
    {
        $pixels = 0;
        foreach ($image as $line) {
            $count = array_count_values($line)['#'] ?? null;
            if ($count) {
                $pixels += $count;
            }
        }

        return $pixels;
    }
}