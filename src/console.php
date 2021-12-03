<?php
declare(strict_types=1);

namespace marvellanius\Advent;

require dirname(__DIR__) . '/vendor/autoload.php';

use marvellanius\Advent\Command\AssignmentCommand;
use marvellanius\Advent\Command\CreateAssignmentCommand;
use Symfony\Component\Console\Application;
use marvellanius\Advent\Command\DownloadInputCommand;

$app = new Application();
$app->add(new DownloadInputCommand());
$app->add(new AssignmentCommand());
$app->add(new CreateAssignmentCommand());
$app->run();