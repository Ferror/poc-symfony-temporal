<?php
declare(strict_types=1);

namespace App\Workflows\Query;

use Carbon\CarbonInterval;
use Temporal\Workflow;

class QueryWorkflow implements QueryWorkflowInterface
{
    private string $message = '';

    public function createGreeting(string $name)
    {
        $this->message = sprintf('Hello, %s!', $name);

        // Always use Workflow::timer() instead of native sleep() and usleep() functions
        yield Workflow::timer(CarbonInterval::seconds(2));

        $this->message = sprintf('Bye, %s!', $name);
    }

    public function queryGreeting(): string
    {
        return $this->message;
    }
}
