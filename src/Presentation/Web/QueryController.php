<?php
declare(strict_types=1);

namespace App\Presentation\Web;

use App\Workflows\Query\QueryWorkflowInterface;
use Carbon\CarbonInterval;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Temporal\Client\WorkflowClientInterface;
use Temporal\Client\WorkflowOptions;

final class QueryController extends AbstractController
{
    public function __construct(
        private WorkflowClientInterface $workflowClient
    ) {
    }

    #[Route(path: '/api/query', name: 'TEMPORAL_QUERY')]
    public function __invoke(): Response
    {
        $workflow = $this->workflowClient->newWorkflowStub(
            QueryWorkflowInterface::class,
            WorkflowOptions::new()->withWorkflowExecutionTimeout(CarbonInterval::minute())
        );

        $run = $this->workflowClient->start($workflow, 'Antony');
        sleep(2);

        // wait for workflow to complete
        $run->getResult();

        return new Response();
    }
}
