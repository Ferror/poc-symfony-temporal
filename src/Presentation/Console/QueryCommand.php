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

class QueryCommand extends Command
{
    protected const NAME = 'query';

    public function __construct(
        private WorkflowClientInterface $workflowClient
    ) {
        parent::__construct(self::NAME);
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $workflow = $this->workflowClient->newWorkflowStub(
            QueryWorkflowInterface::class,
            WorkflowOptions::new()->withWorkflowExecutionTimeout(CarbonInterval::minute())
        );

        $output->writeln("Starting <comment>QueryWorkflow</comment>... ");

        $run = $this->workflowClient->start($workflow, 'Antony');

        $output->writeln(
            sprintf(
                'Started: WorkflowID=<fg=magenta>%s</fg=magenta>, RunID=<fg=magenta>%s</fg=magenta>',
                $run->getExecution()->getID(),
                $run->getExecution()->getRunID(),
            )
        );

        $output->writeln("Querying <comment>QueryWorkflow->queryGreeting</comment>... ");
        $output->writeln(sprintf("Query result:\n<info>%s</info>", $workflow->queryGreeting()));

        $output->writeln("Sleeping for 2 seconds... ");
        sleep(2);
        $output->writeln(sprintf("Query result:\n<info>%s</info>", $workflow->queryGreeting()));

        // wait for workflow to complete
        $run->getResult();

        return self::SUCCESS;
    }
}
