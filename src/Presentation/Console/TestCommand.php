<?php
declare(strict_types=1);

namespace App\Presentation\Console;

use App\Workflows\Query\QueryWorkflowInterface;
use Carbon\CarbonInterval;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Temporal\Client\WorkflowClientInterface;
use Temporal\Client\WorkflowOptions;

class TestCommand extends Command
{
    protected const NAME = 'test';

    public function __construct()
    {
        parent::__construct(self::NAME);
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln("Starting <comment>TestCommand</comment>... ");
        $output->writeln("Started");

        $output->writeln("Querying <comment>QueryWorkflow->queryGreeting</comment>... ");
        $output->writeln(sprintf("Query result:\n<info>%s</info>", 'asd'));

        $output->writeln("Sleeping for 2 seconds... ");
        sleep(2);
        $output->writeln(sprintf("Query result:\n<info>%s</info>", 'ASD'));

        return self::SUCCESS;
    }
}
