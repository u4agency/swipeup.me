<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SwipeController extends AbstractController
{
    #[Route('/wyssual-expert', name: 'app_wyssual-expert')]
    public function wyssualexpert(): Response
    {
        return $this->render('swipe/wyssual-expert.twig', [
            'controller_name' => 'SwipeWyssualExpertController',
        ]);
    }
}
