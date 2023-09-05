<?php

namespace App\Controller;

use App\Entity\URLShortener;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class URLShortenerController extends AbstractController
{
    #[Route('~{slug}', name: 'app_url_shortener')]
    public function index(
        URLShortener $urlShortener,
    ): Response
    {
        return $this->redirect($urlShortener->getLink());
    }
}
