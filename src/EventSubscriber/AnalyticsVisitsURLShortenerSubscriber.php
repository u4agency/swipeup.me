<?php

namespace App\EventSubscriber;

use App\Entity\AnalyticsVisitsURLShortener;
use App\Repository\URLShortenerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;

readonly class AnalyticsVisitsURLShortenerSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private URLShortenerRepository $URLShortenerRepository,
    )
    {
    }

    public function onKernelController(ControllerEvent $event): void
    {
        $analyticsVisitsURLShortener = new AnalyticsVisitsURLShortener();

        $request = $event->getRequest();

        $route = $request->attributes->get('_route');
        $params = $request->attributes->get('_route_params');


        if ($route !== 'app_url_shortener' || !$params['slug']) return;

        $URLShortener = $this->URLShortenerRepository->findOneBy(['slug' => $params['slug']]);

        if (!$URLShortener) return;

        $analyticsVisitsURLShortener
            ->setUserId($request->getSession()->getId())
            ->setURLShortener($URLShortener)
            ->setUserAgent($_SERVER['HTTP_USER_AGENT'])
            ->setUserIp($_SERVER['REMOTE_ADDR'])
            ->setSiteReferer($_SERVER['HTTP_REFERER'] ?? null);

        $this->entityManager->persist($analyticsVisitsURLShortener);
        $this->entityManager->flush();
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::CONTROLLER => 'onKernelController',
        ];
    }
}