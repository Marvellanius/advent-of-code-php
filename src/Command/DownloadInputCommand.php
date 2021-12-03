<?php

namespace marvellanius\Advent\Command;

use RuntimeException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Dotenv\Dotenv;

class DownloadInputCommand extends Command
{
    protected static $defaultName = 'input:download';

    private const DOWNLOAD_TARGET = 'https://adventofcode.com/';
    private const OPTION_DAY = 'day';
    private const OPTION_YEAR = 'year';
    private const OPTION_FORCE = 'force';
    private int $currentYear;
    private int $currentDay;

    protected function configure(): void
    {
        $this->currentYear = date("Y");
        $this->currentDay = min([date("j"), 25]);
        $this->setDescription('Download input for a given day from Advent of Code')
            ->addOption(self::OPTION_YEAR, substr(self::OPTION_YEAR, 0, 1), InputOption::VALUE_REQUIRED, "Current year as int (between 2000-{$this->currentYear}, default current year)", $this->currentYear)
            ->addOption(self::OPTION_DAY, substr(self::OPTION_DAY, 0, 1), InputOption::VALUE_REQUIRED, "Current day as int (between 1-{$this->currentDay}, default {$this->currentDay})", $this->currentDay)
            ->addOption(self::OPTION_FORCE, substr(self::OPTION_FORCE, 0, 1), InputOption::VALUE_NONE)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $helper = $this->getHelper('question');
        $projectRoot = dirname(__DIR__, 2);

        $resourceDir = "{$projectRoot}/resources";

        // If input isn't equal to the default, check if it's a valid input (int, between range, etc)
        if ( $input->getOption(self::OPTION_YEAR) !== $this->currentYear && ! preg_match("/^(20)\d{2}$/", $input->getOption(self::OPTION_YEAR))) {
            $question = new Question("<question>Please input a year (between 2000-{$this->currentYear}, default: {$this->currentYear}): </question>", $this->currentYear);
            $input->setOption(self::OPTION_YEAR, (int)$helper->ask($input, $output, $question));
        }

        if ( $input->getOption(self::OPTION_DAY) !== $this->currentDay && ! preg_match("/^([1-9]|1[\d]|2[0-5])$/", $input->getOption(self::OPTION_DAY))) {
            $question = new Question("<question>Please input a day (between 1-{$this->currentDay}, default: {$this->currentDay}): </question>", $this->currentDay);
            $input->setOption(self::OPTION_DAY, (int)$helper->ask($input, $output, $question));
        }

        $year = (int)$input->getOption(self::OPTION_YEAR);
        $day = (int)$input->getOption(self::OPTION_DAY);

        $output->writeln("<info>Downloading Advent of Code input file for event {$year}, day {$day}</info>");

        $filename = "{$resourceDir}/Y{$year}/Day{$day}.txt";

        // Store our file if the file does not already exist
        if (!file_exists($filename) || $input->getOption(self::OPTION_FORCE)) {
            $folder_name = "{$resourceDir}/Y{$year}";
            if (!is_dir($folder_name) && !mkdir($folder_name, 0775) && !is_dir($folder_name)) {
                throw new RuntimeException(sprintf('Directory "%s" was not created', $folder_name));
            }

            // Load env variables (currently only used for SESSION_KEY
            (new Dotenv())->load("{$projectRoot}/.env");

            $options = [
                "http" => [
                    "method" => "GET",
                    "header" => "Accept-language: en\r\n" .
                        "Cookie: session={$_ENV['SESSION_KEY']}\r\n"
                ]
            ];

            $context = stream_context_create($options);
            if (file_put_contents($filename, file_get_contents(self::DOWNLOAD_TARGET . "{$year}/day/{$day}/input", false, $context))) {
                $output->writeln("<info>File: {$filename} downloaded</info>");
            } else {
                $output->writeln("<info>File could not be saved, check your SESSION_KEY</info>");
            }

            chmod($filename, 0777);
        } else {
            $output->writeln("<error>File: {$filename} already exists! If you want to override this file use --force</error>");
            return Command::FAILURE;
        }
        return Command::SUCCESS;
    }
}