<?php
declare(strict_types=1);

namespace App\Presentation\Console;

use App\Workflows\AsyncActivity\AsyncGreetingWorkflowInterface;
use Carbon\CarbonInterval;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Temporal\Client\WorkflowClientInterface;
use Temporal\Client\WorkflowOptions;

class AsyncActivityCommand extends Command
{
    protected const NAME = 'activity';

    public function __construct(
        private WorkflowClientInterface $workflowClient
    ) {
        parent::__construct(self::NAME);
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $workflow = $this->workflowClient->newWorkflowStub(
            AsyncGreetingWorkflowInterface::class,
            WorkflowOptions::new()->withWorkflowExecutionTimeout(CarbonInterval::minute())
        );

        $output->writeln("Starting <comment>GreetingWorkflow</comment>... ");

        $run = $this->workflowClient->start($workflow, 'Antony');

        $output->writeln(
            sprintf(
                'Started: WorkflowID=<fg=magenta>%s</fg=magenta>, RunID=<fg=magenta>%s</fg=magenta>',
                $run->getExecution()->getID(),
                $run->getExecution()->getRunID(),
            )
        );

        $output->writeln(sprintf("Result:\n<info>%s</info>", $run->getResult()));

        return self::SUCCESS;
    }
}
