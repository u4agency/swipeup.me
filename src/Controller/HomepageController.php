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
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Annotation\Route;

class HomepageController extends AbstractController
{
    #[Route('/', name: 'app_homepage')]
    #[Route('/', name: 'maintenance')]
    public function index(
        SwipeUpRepository      $swipeUpRepository,
        EntityManagerInterface $entityManager,
        Request                $request,
        MaintenanceMode        $maintenanceMode,
        NewsletterRepository   $newsletterRepository,
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
                    return $this->redirectToRoute('app_newsletter', ['code' => $email->getCode()]);
                }

                $newsletter->setSource($request->attributes->get('_route'));
                $referral = $newsletterRepository->findOneBy(['code' => $request->query->get('code')]);
                $referral?->setPoints($referral->getPoints() + 100);
                try {
                    $entityManager->persist($newsletter);
                    if ($referral) $entityManager->persist($referral);
                    $entityManager->flush();
                } catch (\Exception $exception) {
                    $this->addFlash('error', "Une erreur est survenue.");
                }

                return $this->redirectToRoute('app_newsletter', ['code' => $newsletter->getCode()]);
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
            $this->addFlash('error', "Vous n'êtes pas en file d'attente !");
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
}
