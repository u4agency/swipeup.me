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


    #[Route('/omnip-basic', name: 'app_omnip-basic')]
    public function omnipbasic(): Response
    {
        return $this->render('swipe/omnip.html.twig', [

            'controller_name' => 'SwipeOmnipBasicController',
        ]);
    }

    #[Route('/wyssual-basic', name: 'app_wyssual-basic')]
    public function wyssualbasic(): Response
    {
        return $this->render('swipe/wyssual-basic.html.twig', [

            'controller_name' => 'SwipeWyssualBasicController',
        ]);
    }

    #[Route('/wyssbarber', name: 'app_wyssbarber')]
    public function wyssbarber(): Response
    {
        return $this->render('swipe/wyssbarber.html.twig', [

            'controller_name' => 'SwipeWyssbarberController',
        ]);
    }
}
