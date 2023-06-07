<?php

namespace App\Service;

use Sentry\Tracing\SamplingContext;

class Sentry
{
    public function getTracesSampler(): callable
    {
        return function (SamplingContext $context): float {
            if ($context->getParentSampled()) {
                // If the parent transaction (for example a JavaScript front-end)
                // is sampled, also sample the current transaction
                return 1.0;
            }

            // Default sample rate for all other transactions (replaces `traces_sample_rate`)
            return 0.25;
        };
    }
}