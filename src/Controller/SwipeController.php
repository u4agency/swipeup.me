<?php

namespace App\Controller;

use App\Entity\Newsletter;
use App\Entity\Swipe;
use App\Entity\SwipeImage;
use App\Form\NewsletterType;
use App\Form\SwipeUploadType;
use App\Repository\SwipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;

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
    public function singleSwipe(
        Swipe                  $swipe,
        EntityManagerInterface $entityManager,
        Request                $request
    ): Response
    {
        $newsletter = new Newsletter();
        $form = $this->createForm(NewsletterType::class, $newsletter);
        if ($swipe->isHomepageDisplay()) {
            $form->handleRequest($request);
            if ($form->isSubmitted()) {
                if ($form->isValid()) {
                    $newsletter->setSource($request->attributes->get('_route') . " (" . $swipe->getSlug() . ")");
                    try {
                        $entityManager->persist($newsletter);
                        $entityManager->flush();

                        $this->addFlash('success', "Vous êtes bien inscrit à la file d'attente !");
                    } catch (\Exception $exception) {
                        $this->addFlash('danger', "Vous êtes déjà en file d'attente !");
                    }
                }
            }
        }

        return $this->render('swipe/single.html.twig', [
            'controller_name' => 'SwipeController',
            'swipe' => $swipe,
            'newsletterForm' => $form
        ]);
    }

    #[Route('create', name: 'app_swipe_create')]
    public function createSwipe(
        Request                $request,
        EntityManagerInterface $entityManager,
        UploaderHelper         $uploaderHelper
    ): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        return $this->render('swipe/create.html.twig', [
            'controller_name' => 'SwipeController'
        ]);
    }
}
