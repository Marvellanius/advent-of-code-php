<?php

namespace marvellanius\Advent\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;

class CreateAssignmentCommand extends Command
{
    protected static $defaultName = 'assignment:create';

    private const OPTION_DAY = 'day';
    private const OPTION_YEAR = 'year';
    private const OPTION_FORCE = 'force';
    private const OPTION_EXCLUDE_FILES = 'exclude';
    private int $currentYear;
    private int $currentDay;

    protected function configure(): void
    {
        $this->currentYear = date("Y");
        $this->currentDay = min([date("j"), 25]);

        $this->setDescription('Create the classes for a given day from Advent of Code')
            ->addOption(self::OPTION_YEAR, substr(self::OPTION_YEAR, 0, 1), InputOption::VALUE_REQUIRED, "Current year as int (between 2000-{$this->currentYear}, default current year)", $this->currentYear)
            ->addOption(self::OPTION_DAY, substr(self::OPTION_DAY, 0, 1), InputOption::VALUE_REQUIRED, "Current day as int (between 1-{$this->currentDay}, default {$this->currentDay})", $this->currentDay)
            ->addOption(self::OPTION_FORCE, substr(self::OPTION_FORCE, 0, 1), InputOption::VALUE_NONE)
            ->addOption(self::OPTION_EXCLUDE_FILES, substr(self::OPTION_EXCLUDE_FILES, 0, 1), InputOption::VALUE_NONE);
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $helper = $this->getHelper('question');
        $projectRoot = dirname(__DIR__, 2);
        $excludedFiles = [];

        // If input isn't equal to the default, check if it's a valid input (int, between range, etc)
        if ( $input->getOption(self::OPTION_YEAR) !== $this->currentYear && ! preg_match("/^(20)\d{2}$/", $input->getOption(self::OPTION_YEAR))) {
            $question = new Question("<question>Please input a year (between 2000-{$this->currentYear}, default: {$this->currentYear}): </question>", $this->currentYear);
            $input->setOption(self::OPTION_YEAR, (int)$helper->ask($input, $output, $question));
        }

        if ( $input->getOption(self::OPTION_DAY) !== $this->currentDay && ! preg_match("/^([1-9]|1[\d]|2[0-5])$/", $input->getOption(self::OPTION_DAY))) {
            $question = new Question("<question>Please input a day (between 1-25, default: {$this->currentDay}): </question>>", $this->currentDay);
            $input->setOption(self::OPTION_DAY, (int)$helper->ask($input, $output, $question));
        }

        if ( $input->getOption(self::OPTION_EXCLUDE_FILES)) {
            $question = new ChoiceQuestion(
                '<question>Which files would you like to exclude from the creation script?</question>',
                ['testInput', 'dayClass', 'testClass']
            );
            $question->setMultiselect(true);

            $excludedFiles = $helper->ask($input, $output, $question);
        }

        $year = (int)$input->getOption(self::OPTION_YEAR);
        $day = (int)$input->getOption(self::OPTION_DAY);


        $filePaths = [
            'testInput' => [
                'output' => "{$projectRoot}/resources/Y{$year}/Day{$day}-test.txt"
            ],
            'dayClass' => [
                'output' => "{$projectRoot}/src/Y{$year}/Day{$day}.php",
                'template' => "{$projectRoot}/templates/Day.php.template",
            ],
            "testClass" => [
                'output' => "{$projectRoot}/test/unit/Y{$year}/Day{$day}Test.php",
                'template' => "{$projectRoot}/templates/DayTest.php.template",
            ],
        ];

        $filesToGenerate = array_diff(array_keys($filePaths), $excludedFiles);

        foreach ($filesToGenerate as $slug) {
            $target = $filePaths[$slug]['output'];
            $source = $filePaths[$slug]['template'] ?? '';

            if (!file_exists($target) || $input->getOption(self::OPTION_FORCE)) {

                $created = $this->generateFile($target, $source, $year, $day);
                if ($created !== false) {
                    $output->writeln("<info>File: {$target} created</info>");
                } else {
                    $output->writeln("<info>File: {$target} could not be saved, check whether the parent folders exist</info>");
                }
            } else {
                $output->writeln("<error>File: {$target} already exists! If you want to override this file use --force</error>");
                $question = new ConfirmationQuestion("<question>You can overwrite the existing file, would you like to? [y(es)/n(o)] (default: no)</question> ", false);

                if ($helper->ask($input, $output, $question)) {
                    $question = new ConfirmationQuestion("<fg=red;options=bold,underline>Are you sure? [y(es)/n(o)] (default: no)</> ", false);

                    if ($helper->ask($input, $output, $question)) {
                        $this->generateFile($target, $source, $year, $day);
                        $output->writeln("<fg=green;>File: {$target} overwritten!</>");

                        return Command::SUCCESS;
                    }
                }
                return Command::FAILURE;
            }
        }
        return Command::SUCCESS;
    }

    private function generateFile(string $target, string $source, int $year, int $day): bool
    {
        return match (is_file($source)) {
            true => $this->createFileFromTemplate($target, $source, $year, $day),
            default => $this->createEmptyFile($target)
        };
    }

    private function createEmptyFile(string $filePath): bool
    {
        return file_put_contents($filePath, ' ');
    }

    private function createFileFromTemplate(string $outputFilePath, string $templateFilePath, int $year, int $day): bool
    {
        $template = file_get_contents($templateFilePath, false);
        $parsedTemplate = str_replace(['<YEAR_PLACEHOLDER>', '<DAY_PLACEHOLDER>'], [$year, $day], $template);

        return file_put_contents($outputFilePath, $parsedTemplate);
    }
}