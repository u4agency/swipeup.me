<?php

namespace App\Controller;

use App\Entity\Swipe;
use App\Entity\SwipeImage;
use App\Entity\WidgetData;
use App\Entity\WidgetSwipe;
use App\Form\SwipeBackgroundType;
use App\Form\SwipeSectionType;
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
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;

#[Route('/api/')]
#[IsGranted('IS_AUTHENTICATED_FULLY')]
class ApiController extends AbstractController
{
    #[Route('create_swipe', name: '_api_swipe_create')]
    public function swipeCreate(
        Request                $request,
        EntityManagerInterface $entityManager,
        WidgetRepository       $widgetRepository,
        SwipeUpRepository      $swipeUpRepository,
    ): Response
    {
        if (!$this->isGranted('IS_AUTHENTICATED_FULLY') || $this->getUser()->getSwipeUps()->count() < 1) {
            throw new BadRequestHttpException();
        }

        $swipe = new Swipe();

        $section = $this->createForm(SwipeSectionType::class, $swipe)
            ->add('swipeup', HiddenType::class, [
                'mapped' => false,
                'data' => $request->query->get('swipeup')
            ]);
        $section->handleRequest($request);

        if ($section->isSubmitted()) {
            $swipeup = $swipeUpRepository->findOneBy(['slug' => $section->get('swipeup')->getData()]);

            if (!$swipeup || !$this->getUser() || $swipeup->getAuthor() !== $this->getUser()) {
                throw new BadRequestHttpException();
            }

            if (!$section->isValid()) {
                return new Response("Le formulaire n'est pas valide.", 400);
            }

            $swipe->setSwipeup($swipeup);
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
        ]);
    }

    #[Route('widget_select', name: '_api_widget_select')]
    public function getSpecificWidgetSelect(
        Request          $request,
        WidgetRepository $widgetRepository,
    ): Response
    {
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
        $swipeup = $swipeUpRepository->findOneBy(['slug' => $request->attributes->get('slug')]);

        if (!$swipeup || $swipeup->getAuthor() !== $this->getUser()) {
            return new Response('', Response::HTTP_UNAUTHORIZED);
        }

        return $this->render('_components/create/_swipes.html.twig', [
            'swipes' => $swipeup->getSwipes(),
        ]);
    }
}