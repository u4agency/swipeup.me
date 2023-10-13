<?php

namespace App\Controller;

use App\Entity\AnalyticsVisitsSwipe;
use App\Entity\Swipe;
use App\Form\SwipeSectionType;
use App\Repository\AnalyticsVisitsSwipeRepository;
use App\Repository\SwipeRepository;
use App\Repository\SwipeUpRepository;
use App\Repository\WidgetRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api')]
class ApiController extends AbstractController
{
    #[Route('/v1/swipe/visit', name: '_api_analytics_swipe_visit', methods: ['POST'])]
    public function swipeVisit(
        SwipeRepository                $swipeRepository,
        Request                        $request,
        AnalyticsVisitsSwipeRepository $analyticsVisitsSwipeRepository,
        EntityManagerInterface         $entityManager
    ): JsonResponse
    {
        $response = json_decode($request->getContent(), true);

        if (isset($response['id'], $response['_token'])) {
            $swipe = $swipeRepository->findOneBy(['id' => $response['id']]);

            if (!$swipe) {
                return new JsonResponse(['error' => 'Mauvaise requête.'], Response::HTTP_BAD_REQUEST);
            }

            if (!$this->isCsrfTokenValid('analytics' . $swipe->getId(), $response['_token'])) {
                return new JsonResponse(['error' => 'Mauvaise requête.'], Response::HTTP_BAD_REQUEST);
            }

            if (isset($response['exited'])) {
                $analyticsVisitsSwipe = $analyticsVisitsSwipeRepository->findOneBy(['id' => $response['exited'], 'userId' => $request->getSession()->getId()]);

                if (!$analyticsVisitsSwipe) {
                    return new JsonResponse(['error' => 'Mauvaise requête.'], Response::HTTP_BAD_REQUEST);
                }

                $analyticsVisitsSwipe->setExitedAt(new \DateTimeImmutable());

                $entityManager->persist($analyticsVisitsSwipe);
            }

            $analyticsVisitsSwipe = new AnalyticsVisitsSwipe();
            $analyticsVisitsSwipe
                ->setUserId($request->getSession()->getId())
                ->setSwipe($swipe)
                ->setUserAgent($_SERVER['HTTP_USER_AGENT'])
                ->setUserIp($_SERVER['REMOTE_ADDR']);


            $entityManager->persist($analyticsVisitsSwipe);
            $entityManager->flush();

            return new JsonResponse(['message' => 'ok', 'id' => $analyticsVisitsSwipe->getId()], Response::HTTP_OK);
        } elseif (isset($response['exited']) && !isset($response['id'], $response['_token'])) {
            $analyticsVisitsSwipe = $analyticsVisitsSwipeRepository->findOneBy(['id' => $response['exited'], 'userId' => $request->getSession()->getId()]);

            if (!$analyticsVisitsSwipe) {
                return new JsonResponse(['error' => 'Mauvaise requête.'], Response::HTTP_BAD_REQUEST);
            }

            $analyticsVisitsSwipe->setExitedAt(new \DateTimeImmutable());

            $entityManager->persist($analyticsVisitsSwipe);
            $entityManager->flush();
            return new JsonResponse(['message' => 'ok'], Response::HTTP_OK);
        } else {
            return new JsonResponse(['error' => 'Mauvaise requête.'], Response::HTTP_BAD_REQUEST);
        }
    }


    #[Route('/create_swipe', name: '_api_swipe_create')]
    public function swipeCreate(
        Request                $request,
        EntityManagerInterface $entityManager,
        SwipeUpRepository      $swipeUpRepository,
    ): Response
    {
        if (!$this->getUser() || $this->getUser()->getSwipeUps()->count() < 1) {
            throw new BadRequestHttpException();
        }

        $swipe = new Swipe();

        $section = $this->createForm(SwipeSectionType::class, $swipe)
            ->add('swipeup', HiddenType::class, [
                'mapped' => false,
                'data' => $request->query->get('swipeup')
            ]);
        $section->handleRequest($request);

        $swipeup = $swipeUpRepository->findOneBy(['slug' => $section->get('swipeup')->getData()]);

        if ($section->isSubmitted()) {
            if (!$swipeup || !$this->getUser() || $swipeup->getAuthor() !== $this->getUser()) {
                throw new BadRequestHttpException();
            }

            if (!$section->isValid()) {
                return new Response("Le formulaire n'est pas valide.", 400);
            }

            $swipe->setSwipeup($swipeup);
            $swipe->setSequence($swipeup->getSwipes()->count() + 1);
            try {
                $entityManager->persist($swipe);
                $entityManager->flush();
            } catch (\Exception $exception) {
                return new Response('Une erreur est survenue.', 500);
            }

            return new Response('Le Swipe a bien été créé !');
        }

        return $this->render('_components/create/form_create.html.twig', [
            'form' => $section->createView(),
            'action' => $this->generateUrl('_api_swipe_create'),
            'swipeup' => $swipeup,
            'background' => $swipe->getBackground(),
        ]);
    }

    #[Route('/widget_select', name: '_api_widget_select')]
    public function getSpecificWidgetSelect(
        Request          $request,
        WidgetRepository $widgetRepository,
    ): Response
    {
        if (!$this->getUser() || $this->getUser()->getSwipeUps()->count() < 1) {
            throw new BadRequestHttpException();
        }

        $widget = $widgetRepository->findOneBy(['id' => $request->query->get('widgetName')]);

        if (!$widget) {
            return new Response();
        }

        $swipe = new Swipe();

        $form = $this->createForm(SwipeSectionType::class, $swipe, [
            $request->query->get('widget') . 'Value' => $widget->getName(),
        ]);
        $form->get($request->query->get('widget'))->setData($widget);


        return $this->render('_components/create/form/' . $widget->getName() . '.html.twig', [
            'form' => $form->createView(),
            'widget' => $request->query->get('widget'),
        ]);
    }

    public function getSwipes(
        Request           $request,
        SwipeUpRepository $swipeUpRepository,
    ): Response
    {
        if (!$this->getUser() || $this->getUser()->getSwipeUps()->count() < 1) {
            throw new BadRequestHttpException();
        }

        $swipeup = $swipeUpRepository->findOneBy(['slug' => $request->attributes->get('slug')]);

        if (!$swipeup || $swipeup->getAuthor() !== $this->getUser()) {
            return new Response('', Response::HTTP_UNAUTHORIZED);
        }

        return $this->render('_components/create/_swipes.html.twig', [
            'swipes' => $swipeup->getSwipes(),
        ]);
    }
}