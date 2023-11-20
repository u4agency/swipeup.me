<?php

namespace App\Twig;

use App\Repository\SwipeUpRepository;
use App\Repository\URLShortenerRepository;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class SwipeListExtension extends AbstractExtension
{
    public function __construct(
        private readonly SwipeUpRepository      $swipeUpRepository,
        private readonly URLShortenerRepository $urlShortenerRepository,
    )
    {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('personalSwipes', [$this, 'personalSwipes']),
            new TwigFunction('personalURLShorteners', [$this, 'personalURLShorteners']),
        ];
    }

    public function personalSwipes($author, $isAdmin = false): array
    {
        return $this->swipeUpRepository->getAll($author, $isAdmin);
    }

    public function personalURLShorteners($author): array
    {
        return $this->urlShortenerRepository->findBy(['createdBy' => $author], ['createdAt' => 'DESC']);
    }
}