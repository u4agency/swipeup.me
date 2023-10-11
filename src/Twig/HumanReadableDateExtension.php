<?php

namespace App\Twig;

use Carbon\Carbon;
use DateTimeInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class HumanReadableDateExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('dateHR', [$this, 'dateHR']),
        ];
    }

    public function dateHR(DateTimeInterface $date, string $locale = 'en'): string
    {
        Carbon::setLocale($locale);
        $carbonDate = Carbon::parse($date);

        return $carbonDate->diffForHumans();
    }
}