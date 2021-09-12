<?php
declare(strict_types=1);

namespace App\Workflows\Query;

use Temporal\Workflow\QueryMethod;
use Temporal\Workflow\WorkflowInterface;
use Temporal\Workflow\WorkflowMethod;

#[WorkflowInterface]
interface QueryWorkflowInterface
{
    /**
     * @param string $name
     * @return string
     */
    #[WorkflowMethod(name: "QueryWorkflow.createGreeting")]
    public function createGreeting(
        string $name
    );

    #[QueryMethod]
    public function queryGreeting(): string;
}
