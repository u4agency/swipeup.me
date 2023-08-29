<?php

namespace App\Controller;

use App\Entity\Newsletter;
use App\Entity\Swipe;
use App\Entity\SwipeImage;
use App\Entity\SwipeUp;
use App\Entity\WidgetData;
use App\Entity\WidgetSwipe;
use App\Form\NewsletterType;
use App\Form\SwipeBackgroundType;
use App\Form\SwipeSectionType;
use App\Form\SwipeUpCreateType;
use App\Form\SwipeUpEditType;
use App\Repository\SwipeRepository;
use App\Repository\SwipeUpRepository;
use App\Repository\WidgetRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;

#[Route('/')]
class SwipeController extends AbstractController
{
    #[Route('swipeup', name: 'app_swipeup')]
    public function allSwipeUps(
        SwipeRepository $swipeRepository,
    ): Response
    {
        return $this->render('swipe/index.html.twig', [
            'swipes' => $swipeRepository->findAll()
        ]);
    }

    #[Route('@{slug}', name: 'app_swipeup_single', priority: -1)]
    public function singleSwipeUp(
        SwipeUp                $swipeup,
        EntityManagerInterface $entityManager,
        Request                $request
    ): Response
    {
        $newsletter = new Newsletter();
        $form = $this->createForm(NewsletterType::class, $newsletter);
        if ($swipeup->isFeaturedSwipeUp()) {
            $form->handleRequest($request);
            if ($form->isSubmitted()) {
                if ($form->isValid()) {
                    $newsletter->setSource($request->attributes->get('_route') . " (" . $swipeup->getSlug() . ")");
                    try {
                        $entityManager->persist($newsletter);
                        $entityManager->flush();

                        $this->addFlash('success', "Vous êtes bien inscrit à la file d'attente !");
                    } catch (\Exception $exception) {
                        $this->addFlash('error', "Vous êtes déjà en file d'attente !");
                    }
                }
            }
        }

        return $this->render('swipe/single.html.twig', [
            'swipeup' => $swipeup,
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
        if (!$this->getUser() && !$this->isGranted('ROLE_FIRST')) {
            return $this->redirectToRoute('app_login');
        }

        $swipeup = new SwipeUp();
        $form = $this->createForm(SwipeUpCreateType::class, $swipeup);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (!$this->getUser()->getSwipeUps()->count() <= 1 && !$this->isGranted('ROLE_ADMIN')) {
                $this->addFlash('error', 'Vous avez atteint la limite de création de SwipeUp !');
                return $this->redirectToRoute('app_homepage');
            }

            $swipeup->setTitle("SwipeUp de " . $this->getUser());
            $swipeup->setDescription("Ceci est le SwipeUP de " . $this->getUser() . " !");
            $swipeup->setStatus("public");
            $swipeup->setAuthor($this->getUser());
            $entityManager->persist($swipeup);
            $entityManager->flush();

            return $this->redirectToRoute('app_user_swipeup_edit', ['slug' => $swipeup->getSlug()], Response::HTTP_SEE_OTHER);
        }


        return $this->render('swipe/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('random', name: 'app_swipe_randomswipeup')]
    public function randomSwipeUp(
        SwipeUpRepository $swipeupRepository,
    ): RedirectResponse
    {
        return $this->redirectToRoute('app_swipeup_single', [
            'slug' => $swipeupRepository->randomRow()[0]['slug']
        ]);
    }

    public function _sectionCreate(
        Request                $request,
        EntityManagerInterface $entityManager,
    ): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $swipe = new Swipe();
        $swipeForm = $this->createForm(SwipeSectionType::class, $swipe);
        $swipeForm->handleRequest($request);

        $swipeImage = new SwipeImage();
        $backgroundForm = $this->createForm(SwipeBackgroundType::class, $swipeImage);

        if ($swipeForm->isSubmitted() && $swipeForm->isValid()) {
            $entityManager->persist($swipe);
            $entityManager->flush();

            if ($request->isXmlHttpRequest()) {
                return new Response(null, 204);
            }
        }

        return $this->render('swipe/_create_section.html.twig', [
            'swipeForm' => $swipeForm->createView(),
            'backgroundForm' => $backgroundForm->createView(),
            'sectionCount' => $request->query->get('sectionCount'),
        ]);
    }
}
