<?php

namespace App\Twig;

use App\Service\Status;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class StatusExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('status', [$this, 'getStatus']),
        ];
    }

    public function getStatus(string $name): ?string
    {
        return Status::READABLE_STATUS[$name] ?? "N/A";
    }
}