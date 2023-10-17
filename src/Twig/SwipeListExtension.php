<?php

namespace App\Twig;

use App\Repository\SwipeUpRepository;
use App\Service\Status;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class SwipeListExtension extends AbstractExtension
{
    public function __construct(private readonly SwipeUpRepository $swipeUpRepository)
    {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('personalSwipes', [$this, 'personalSwipes']),
        ];
    }

    public function personalSwipes($author, $isAdmin = false): array
    {
        return $this->swipeUpRepository->getAll($author, $isAdmin);
    }
}