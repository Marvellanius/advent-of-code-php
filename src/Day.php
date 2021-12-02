<?php
declare(strict_types=1);

namespace marvellanius\Advent;

use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Dick van Viegen <dick@tweakers.net>
 */
abstract class Day implements DayInterface
{
    private string $file;

    public function run(OutputInterface $output, int $assignment): void
    {
        switch ($assignment) {
            case 1:
                $output->writeln("Answer to assignment 1: {$this->run1()}");
                break;
            case 2:
                $output->writeln("Answer to assignment 2: {$this->run2()}");
                break;
            default:
                $output->writeln("Answer to assignment 1: {$this->run1()}");
                $output->writeln("Answer to assignment 2: {$this->run2()}");
                break;
        }
    }

    public function setInput(string $file): void
    {
        $this->file = $file;
    }

    public function getInputAsArray(bool $explode = false, string $delimiter = null): array
    {
        $array = [];
        $file = fopen($this->file, "rb");
        if ($file) {
            while (($line = fgets($file)) !== false) {
                if ($explode) {
                    $array[] = explode($delimiter, $line);
                } else {
                    $array[] = $line;
                }
            }
            fclose($file);
        } else {
            throw new \Error("File could not be opened");
        }
        return $array;
    }
}