<?php

namespace App\Controller;

use App\Entity\Swipe;
use App\Repository\SwipeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/')]
class SwipeController extends AbstractController
{
    #[Route('swipe', name: 'app_swipe')]
    public function allSwipe(
        SwipeRepository $swipeRepository,
    ): Response
    {
        return $this->render('swipe/index.html.twig', [
            'controller_name' => 'SwipeController',
            'swipes' => $swipeRepository->findAll()
        ]);
    }

    #[Route('@{slug}', name: 'app_swipe_single', priority: -1)]
    public function singleSwipe(Swipe $swipe): Response
    {
        return $this->render('swipe/single.html.twig', [
            'controller_name' => 'SwipeController',
            'swipe' => $swipe
        ]);
    }


    #[Route('omnip-basic', name: 'app_omnip-basic')]
    public function omnipbasic(): Response
    {
        return $this->render('swipe/omnip.html.twig', [

            'controller_name' => 'SwipeOmnipBasicController',
        ]);
    }

    #[Route('wyssual-basic', name: 'app_wyssual-basic')]
    public function wyssualbasic(): Response
    {
        return $this->render('swipe/wyssual-basic.html.twig', [

            'controller_name' => 'SwipeWyssualBasicController',
        ]);
    }

    #[Route('wyssbarber', name: 'app_wyssbarber')]
    public function wyssbarber(): Response
    {
        return $this->render('swipe/wyssbarber.html.twig', [

            'controller_name' => 'SwipeWyssbarberController',
        ]);
    }


    #[Route('domino', name: 'app_wyssbarber')]
    public function domino(): Response
    {
        return $this->render('swipe/domino.html.twig', [

            'controller_name' => 'SwipeDominoController',
        ]);
    }


    #[Route('sobella', name: 'sobella')]
    public function sobella(): Response
    {
        return $this->render('swipe/sobella.html.twig', [

            'controller_name' => 'SwipeSoBellaController',
        ]);
    }


}
