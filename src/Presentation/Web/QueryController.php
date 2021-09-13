<?php
declare(strict_types=1);

namespace App\Presentation\Web;

use App\Workflows\Query\QueryWorkflowInterface;
use Carbon\CarbonInterval;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Temporal\Client\GRPC\ServiceClient;
use Temporal\Client\WorkflowClient;
use Temporal\Client\WorkflowOptions;

final class QueryController extends AbstractController
{
    private WorkflowClient $workflowClient;

    public function __construct()
    {
        $this->workflowClient = WorkflowClient::create(ServiceClient::create('temporal:7233'));
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
