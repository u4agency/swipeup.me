<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class DumpDieExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('dd', [$this, 'dumpanddie']),
        ];
    }

    public function dumpanddie($text): string
    {
        dd($text);
    }
}