<?php

namespace App\EventSubscriber;

use App\Entity\AnalyticsVisitsSwipeUp;
use App\Entity\URLShortener;
use App\Repository\AnalyticsVisitsSwipeUpRepository;
use App\Repository\SwipeUpRepository;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;

readonly class URLShortenerSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private Security $security,
    )
    {
    }

    public function beforeEntityPersistedEvent(BeforeEntityPersistedEvent $event): void
    {
        $entity = $event->getEntityInstance();

        if (!($entity instanceof URLShortener)) {
            return;
        }

        $slug = uniqid();
        $createdBy = $this->security->getUser();
        $entity->setCreatedBy($createdBy);
        $entity->setSlug($slug);
    }

    public static function getSubscribedEvents(): array
    {
        return [
            BeforeEntityPersistedEvent::class => 'beforeEntityPersistedEvent',
        ];
    }
}