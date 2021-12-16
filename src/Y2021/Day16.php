<?php
declare(strict_types=1);

namespace marvellanius\Advent\Y2021;

use marvellanius\Advent\Answer;
use marvellanius\Advent\AbstractDay;
use PhpParser\Node\Param;

final class Day16 extends AbstractDay
{
    private ?string $input = null;

    public function run1(): Answer
    {
        if ($this->input) {
            $hex = $this->input;
        } else {
            $hex = trim($this->getInputAsArray()[0]);
        }

        $binString = $this->parseHex2Bin($hex);

        $result = $this->decodePackage($binString);

        return new Answer((string) $result['versionSum']);
    }

    public function run2(): Answer
    {
        if ($this->input) {
            $hex = $this->input;
        } else {
            $hex = trim($this->getInputAsArray()[0]);
        }

        $binString = $this->parseHex2Bin($hex);

        $result = $this->decodePackage($binString);

        return new Answer((string) $result['value']);
    }

    private function decodePackage(string $bin, int $maxSubPackages = null, ?int $operator = null): array
    {
        $value = null;
        $versionSum = 0;
        $handledPackages = 0;
        while ($bin !== "") {
            if (strlen($bin) < 11) {
                $bin = "";
                break;
            }
            $packageInfo = $this->getHeaderValues($bin);

            $bin = substr($bin, 6);
            $versionSum += $packageInfo['version'];

            if ($packageInfo['typeId'] === 4) {
                $literal = $this->getValueFromLiteral($bin);
                $bin = $literal['bin'];

                if ($operator !== null) {
                    $value = $this->getOperatedValue($value, $literal['value'], $operator);
                } else {
                    $value = $literal['value'];
                }
            } else {
                if ($operator === null) {
                    $operator = $packageInfo['typeId'];
                }
                if ($packageInfo['lengthId'] === "0") {
                    // handle a total number of packets contained in the amount of bits determined by the next 15 bits
                    $totalBits = bindec(substr($bin, 1, 15));
                    $result = $this->decodePackage(substr($bin, 16, $totalBits), operator: $packageInfo['typeId']);
                    $bin = substr($bin, 16 + $totalBits);
                } elseif ($packageInfo['lengthId'] === "1") {
                    // handle a number of subpackets, determined by the next 11 bits
                    $numPackages = bindec(substr($bin, 1, 11));
                    $result = $this->decodePackage(substr($bin, 12), $numPackages, $packageInfo['typeId']);
                    $bin = $result['bin'];
                }

                $value = $this->getOperatedValue($value, $result['value'], $operator);
                $versionSum += $result['versionSum'];
            }
            if ($maxSubPackages) {
                $handledPackages++;
                if ($handledPackages === $maxSubPackages) {
                    break;
                }
            }
        }
        $result = [
            'value' => $value,
            'versionSum' => $versionSum,
            'bin' => $bin,
        ];
        return $result;
    }

    private function getOperatedValue(?int $value, int $operatorValue, int $operator)
    {
        if ($value === null) {
            return $operatorValue;
        }
        $operatedValue =  match ($operator) {
            0 => $value + $operatorValue,
            1 => $value * $operatorValue,
            2 => min($value, $operatorValue),
            3 => max($value, $operatorValue),
            5 => $value > $operatorValue ? 1 : 0,
            6 => $value < $operatorValue ? 1 : 0,
            7 => $value === $operatorValue ? 1 : 0,
        };

        echo "Value: {$value}, OperatorValue: {$operatorValue}, Operator Type: {$operator} and resulting Value: {$operatedValue} \r\n";
        return $operatedValue;
    }

    private function getValueFromLiteral(string $bin): array
    {
        $stopped = false;
        $valueString = "";
        while (! $stopped) {
            $valueString .= substr($bin, 1, 4);
            if ($bin[0] === "0") {
                if (strlen($bin) < 11) {
                    $bin = "";
                }
                $stopped = true;
            }
            $bin = substr($bin, 5);
        }
        $value = bindec($valueString);
        return [
            'value' => $value,
            'bin' => $bin,
        ];
    }

    private function getHeaderValues(string $bin): array
    {
        return [
            'version' => bindec(substr($bin, 0, 3)),
            'typeId' => bindec(substr($bin, 3, 3)),
            'lengthId' => substr($bin, 6, 1),
        ];
    }

    private function parseHex2Bin(string $hex): string
    {
        $hexMap = [
            '0' => '0000',
            '1' => '0001',
            '2' => '0010',
            '3' => '0011',
            '4' => '0100',
            '5' => '0101',
            '6' => '0110',
            '7' => '0111',
            '8' => '1000',
            '9' => '1001',
            'A' => '1010',
            'B' => '1011',
            'C' => '1100',
            'D' => '1101',
            'E' => '1110',
            'F' => '1111',
        ];

        $binString = '';

        foreach (str_split($hex) as $hexChar) {
            $binString .= $hexMap[$hexChar];
        }

        return $binString;
    }

    public function setInputString(string $input): void
    {
        $this->input = $input;
    }
}