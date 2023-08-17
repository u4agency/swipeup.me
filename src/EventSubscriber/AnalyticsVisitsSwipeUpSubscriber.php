<?php

namespace App\EventSubscriber;

use App\Entity\AnalyticsVisitsSwipeUp;
use App\Repository\AnalyticsVisitsSwipeUpRepository;
use App\Repository\SwipeUpRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class AnalyticsVisitsSwipeUpSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly SwipeUpRepository      $swipeUpRepository,
    )
    {
    }

    public function onKernelController(ControllerEvent $event): void
    {
        $analyticsVisitsSwipeUp = new AnalyticsVisitsSwipeUp();

        $request = $event->getRequest();

        $route = $request->attributes->get('_route');
        $params = $request->attributes->get('_route_params');


        if ($route !== 'app_swipeup_single' || !$params['slug']) return;

        $swipeup = $this->swipeUpRepository->findOneBy(['slug' => $params['slug']]);

        $analyticsVisitsSwipeUp
            ->setUserId($request->getSession()->getId())
            ->setSwipeup($swipeup)
            ->setUserAgent($_SERVER['HTTP_USER_AGENT'])
            ->setUserIp($_SERVER['REMOTE_ADDR'])
            ->setSiteReferer($_SERVER['HTTP_REFERER'] ?? null);

        $this->entityManager->persist($analyticsVisitsSwipeUp);
        $this->entityManager->flush();
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::CONTROLLER => 'onKernelController',
        ];
    }
}