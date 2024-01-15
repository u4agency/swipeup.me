<?php

namespace App\Controller;

use App\Entity\WidgetSwipe;
use App\Entity\WNewsletter;
use App\Form\Widgets\User\UserNewsletterType;
use App\Repository\WNewsletterRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class UserWidgetsController extends AbstractController
{
    #[Route('/api/user/widgets/newsletter/{id}', name: '_api_user_widgets_newsletter', methods: ['POST'])]
    public function newsletter(
        WidgetSwipe            $widgetSwipe,
        Request                $request,
        EntityManagerInterface $entityManager,
        WNewsletterRepository  $wNewsletterRepository,
    ): Response
    {
        $wnewsletter = new WNewsletter();
        $wnewsletter->setWidgetSwipe($widgetSwipe);
        $form = $this->createForm(UserNewsletterType::class, $wnewsletter);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $wnewsletter = $form->getData();
            $alreadyExist = (bool)$wNewsletterRepository->findOneBy(['email' => $wnewsletter->getEmail(), 'widgetSwipe' => $widgetSwipe]);
            $entityManager->persist($wnewsletter);

            if ($alreadyExist) {
                return new Response("<p class='text-swipe-600'>Vous êtes déjà inscrit à cette newsletter !</p>");
            }

            $entityManager->flush();
            return new Response("<p class='text-green-600'>Votre inscription à la newsletter a bien été prise en compte !</p>");
        }

        return $this->render('user/widgets/newsletter.html.twig', [
            'form' => $form->createView(),
            'widgetSwipe' => $widgetSwipe,
        ]);
    }
}
