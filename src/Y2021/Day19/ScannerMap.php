<?php
declare(strict_types=1);


namespace marvellanius\Advent\Y2021\Day19;

final class ScannerMap
{
    private array $scanners = [];
    public function __construct(Scanner ...$scanners)
    {
        foreach ($scanners as $scanner) {
            $this->scanners[$scanner->getId()] = $scanner;
        }
    }

    public function get(int $id): Scanner
    {
        return $this->scanners[$id];
    }

    public function getOrigin(): Scanner
    {
        return $this->scanners[0];
    }

    /** @return Scanner[] */
    public function toArray(): array
    {
        return $this->scanners;
    }
}