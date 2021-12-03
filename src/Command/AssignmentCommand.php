<?php
declare(strict_types=1);

namespace marvellanius\Advent\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\Question;

/**
 * @author Dick van Viegen <dick@tweakers.net>
 */
final class AssignmentCommand extends Command
{
    protected static $defaultName = 'assignment:run';

    private const OPTION_ASSIGNMENT = 'assignment';
    private const OPTION_YEAR = 'year';
    private const OPTION_DAY = 'day';
    private const OPTION_TEST = 'test';

    private array $yearOptions;
    private array $dayOptions;

    protected function configure(): void
    {
        $this->setOptions();

        $this->setDescription('Download input for a given day from Advent of Code')
            ->addOption(self::OPTION_YEAR, substr(self::OPTION_YEAR, 0, 1), InputOption::VALUE_REQUIRED, default: $this->yearOptions[0])
            ->addOption(self::OPTION_DAY, substr(self::OPTION_DAY, 0, 1), InputOption::VALUE_REQUIRED)
            ->addOption(self::OPTION_ASSIGNMENT, substr(self::OPTION_ASSIGNMENT, 0, 1), InputOption::VALUE_REQUIRED, "Assignment part to run", 0)
            ->addOption(self::OPTION_TEST, substr(self::OPTION_TEST, 0, 1), InputOption::VALUE_NONE)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $helper = $this->getHelper('question');
        $selectedYear = $input->getOption(self::OPTION_YEAR);

        if ( ! in_array($selectedYear, $this->yearOptions, true)) {
            $question = new ChoiceQuestion("<question>Please select the year from which to run an assignment (default: {$this->yearOptions[0]})</question>", $this->yearOptions, 0);
            $selectedYear = $helper->ask($input, $output, $question);
        }

        $this->setDayOptionsForYear($selectedYear);

        $selectedDay = $input->getOption(self::OPTION_DAY);
        if ( ! in_array($selectedDay, $this->dayOptions[$selectedYear], true)) {
            $question = new ChoiceQuestion("<question>Please select the day to run the assignments for (default: {$this->dayOptions[$selectedYear][0]})</question>", $this->dayOptions[$selectedYear], 0);
            $selectedDay = basename($helper->ask($input, $output, $question), '.php');
        }


        $resourceDir = dirname(__DIR__, 2) . "/resources/Y{$selectedYear}";
        $class = "marvellanius\\Advent\\Y{$selectedYear}\\Day{$selectedDay}";
        $day = new $class();

        $day->setInput("{$resourceDir}/Day{$selectedDay}.txt");
        if ($input->getOption(self::OPTION_TEST)) {
            $day->setInput("{$resourceDir}/Day{$selectedDay}-test.txt");
        }

        $assignment = 0;
        $output->writeln("<info>Running assignments for: Y{$selectedYear}/Day{$selectedDay}</info>");

        $day->run($output, $assignment);

        return Command::SUCCESS;
    }

    private function setOptions(): void
    {
        $this->setYearOptions();

        foreach ($this->yearOptions as $year) {
            $this->setDayOptionsForYear($year);
        }
    }

    private function setYearOptions(): void
    {
        $this->yearOptions = array_reverse(
            array_map(
                static fn ($dirName) => substr($dirName, 1),
                array_filter(
                    array_diff(
                        scandir(dirname(__DIR__)), ['..', '.']
                    ),
                    static fn ($dir) => str_starts_with($dir, 'Y')
                )
            )
        );
    }

    private function setDayOptionsForYear(string $year): void
    {
        $this->dayOptions[$year] = array_reverse(
            array_map( static fn ($day) => substr(basename($day, '.php'), 3),
                array_diff(scandir(dirname(__DIR__) . "/Y{$year}"), ['..', '.'])
            )
        );
    }
}