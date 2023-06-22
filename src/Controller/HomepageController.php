<?php

namespace App\Controller;

use App\Entity\Newsletter;
use App\Form\NewsletterType;
use App\Repository\NewsletterRepository;
use App\Repository\SwipeUpRepository;
use App\Utils\MaintenanceMode;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomepageController extends AbstractController
{
    #[Route('/', name: 'app_homepage')]
    #[Route('/', name: 'maintenance')]
    public function index(
        SwipeUpRepository      $swipeUpRepository,
        EntityManagerInterface $entityManager,
        Request                $request,
        MaintenanceMode        $maintenanceMode
    ): Response
    {
        if ($maintenanceMode->isMaintenanceMode() && !$this->isGranted('ROLE_ADMIN')) {
            return $this->render('maintenance/index.html.twig', [

            ], new Response(null, Response::HTTP_SERVICE_UNAVAILABLE, [
                'Retry-After' => 3600
            ]));
        }

        $newsletter = new Newsletter();
        $form = $this->createForm(NewsletterType::class, $newsletter);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $newsletter->setSource($request->attributes->get('_route'));
                try {
                    $entityManager->persist($newsletter);
                    $entityManager->flush();

                    return $this->redirectToRoute('app_newsletter', ['code' => $newsletter->getCode()]);
                } catch (\Exception $exception) {
                    $this->addFlash('danger', "Vous êtes déjà en file d'attente !");
                }
            }
        }

        return $this->render('homepage/index.html.twig', [
            'controller_name' => 'HomepageController',
            'swipeups' => $swipeUpRepository->findBy(['featuredSwipeUp' => true]),
            'newsletterForm' => $form->createView(),
        ]);
    }

    #[Route('/waiting/{code}', name: 'app_newsletter')]
    public function newsletter(
        NewsletterRepository $newsletterRepository,
                             $code,
    ): Response
    {
        $newsletter = $newsletterRepository->findOneBy(['code' => $code]);

        if (!$newsletter) {
            $this->addFlash('danger', "Vous n'êtes pas en file d'attente !");
            return $this->redirectToRoute('app_homepage');
        }

        $allNewsletter = $newsletterRepository->findBy([], ['points' => 'DESC']);
        $position = array_search($newsletter, $allNewsletter) + 1;

        return $this->render('pages/newsletter.html.twig', [
            'controller_name' => 'HomepageController',
            'newsletter' => $newsletter,
            'position' => $position,
        ]);
    }

}
