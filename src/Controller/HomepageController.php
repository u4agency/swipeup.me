<?php

namespace App\Controller;

use App\Entity\Newsletter;
use App\Form\NewsletterType;
use App\Repository\AnalyticsVisitsSwipeRepository;
use App\Repository\NewsletterRepository;
use App\Repository\PagesRepository;
use App\Repository\SwipeRepository;
use App\Repository\SwipeUpRepository;
use App\Utils\MaintenanceMode;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Attribute\Route;

class HomepageController extends AbstractController
{
    #[Route('/', name: 'app_homepage')]
    #[Route('/', name: 'maintenance')]
    public function index(
        SwipeUpRepository              $swipeUpRepository,
        AnalyticsVisitsSwipeRepository $analyticsVisitsSwipe,
        SwipeRepository                $swipeRepository,
        EntityManagerInterface         $entityManager,
        Request                        $request,
        MaintenanceMode                $maintenanceMode,
        NewsletterRepository           $newsletterRepository,
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
                $email = $newsletterRepository->findOneBy(['email' => $form->get('email')->getData()]);

                if ($email) {
                    $this->addFlash('error', "Vous êtes déjà en file d'attente !");

                    return $this->redirectToRoute('app_homepage');
                }

                $newsletter->setSource($request->attributes->get('_route'));
                try {
                    $entityManager->persist($newsletter);
                    $entityManager->flush();

                    $this->addFlash('success', "Vous êtes inscrit à la newsletter !");
                } catch (\Exception $exception) {
                    $this->addFlash('error', "Une erreur est survenue.");
                }

                return $this->redirectToRoute('app_homepage');
            }
        }

        return $this->render('homepage/index.html.twig', [
            'controller_name' => 'HomepageController',
            'swipeups' => $swipeUpRepository->findBy(['featuredSwipeUp' => true]),
            'newsletterForm' => $form->createView(),
            'numbers' => [
                'swipes' => $analyticsVisitsSwipe->countAll(),
                'swipeups' => $swipeUpRepository->countAll(),
                'sections' => $swipeRepository->countAll(),
            ],
        ]);
    }

    #[Route('/new', name: 'app_new')]
    public function new(SwipeUpRepository $swipeUpRepository,): Response
    {
        return $this->render('pages/new.html.twig', [
            'controller_name' => 'HomepageController',
            'swipeups' => $swipeUpRepository->findBy(['featuredSwipeUp' => true]),
        ]);
    }

    #[Route('/changelog', name: 'app_changelog')]
    public function changelog(
        KernelInterface $appKernel
    ): Response
    {
        $file = $appKernel->getProjectDir() . '/CHANGELOG.md';

        if (file_exists($file)) {
            $changelogContent = file_get_contents($file);
        } else {
            $changelogContent = 'Le fichier CHANGELOG.md est introuvable.';
        }

        return $this->render('pages/changelog.html.twig', [
            'changeLogContent' => $changelogContent,
        ]);
    }

    #[Route('/{slug}', name: 'app_page', priority: -2)]
    public function page(PagesRepository $pagesRepository, $slug): Response
    {
        $page = $pagesRepository->findOneBy(['slug' => $slug]);

        if (!$page) {
            throw $this->createNotFoundException("Cette page n'existe pas");
        }

        return $this->render('pages/index.html.twig', [
            'page' => $page,
        ]);
    }

    #[Route('/notyf', name: 'app_notyf')]
    public function notyf(): Response
    {
        $this->addFlash('success', 'Ceci est un message de succès');
        $this->addFlash('info', 'Ceci est un message d\'information');
        $this->addFlash('warning', 'Ceci est un message d\'avertissement');
        $this->addFlash('error', 'Ceci est un message d\'erreur');

        return $this->redirectToRoute('app_homepage');
    }
}
