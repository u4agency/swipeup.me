<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SwipeController extends AbstractController
{
    #[Route('/swipe', name: 'app_swipe')]
    public function index(): Response
    {
        return $this->render('swipe/index.html.twig', [
            'controller_name' => 'SwipeController',
        ]);
    }
}
