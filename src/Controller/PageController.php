<?php

namespace App\Controller;

use App\Repository\PostsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PageController extends AbstractController
{
    #[Route('/about', name: 'app_about')]
    public function about(
        PostsRepository $postsRepository,
    ): Response
    {
        return $this->render('pages/about.html.twig', [
            'news' =>$postsRepository->findBy(['status' => 'published'], ['id' => 'DESC'], 3),
        ]);
    }
}
