<?php

namespace App\Controller;

use App\Entity\Newsletter;
use App\Form\NewsletterType;
use App\Repository\SwipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomepageController extends AbstractController
{
    #[Route('/', name: 'app_homepage')]
    public function index(
        SwipeRepository        $swipeRepository,
        EntityManagerInterface $entityManager,
        Request                $request
    ): Response
    {
        $newsletter = new Newsletter();
        $form = $this->createForm(NewsletterType::class, $newsletter);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $newsletter->setSource($request->attributes->get('_route'));
                try {
                    $entityManager->persist($newsletter);
                    $entityManager->flush();

                    $this->addFlash('success', "Vous êtes bien inscrit à la file d'attente !");
                } catch (\Exception $exception) {
                    $this->addFlash('danger', "Vous êtes déjà en file d'attente !");
                }
            }
        }

        return $this->render('homepage/index.html.twig', [
            'controller_name' => 'HomepageController',
            'swipes' => $swipeRepository->findAll(),
            'newsletterForm' => $form->createView(),
        ]);
    }
}
