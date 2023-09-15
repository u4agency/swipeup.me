<?php

namespace App\EventSubscriber;

use App\Entity\Pages;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class PagesSubscriber implements EventSubscriberInterface
{
    private TokenStorageInterface $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            BeforeEntityPersistedEvent::class => ['setPageAuthor'],
        ];
    }

    public function setPageAuthor(
        BeforeEntityPersistedEvent $event,
    ): void
    {
        $entity = $event->getEntityInstance();

        if (!($entity instanceof Pages)) {
            return;
        }

        $entity->setAuthor($this->tokenStorage->getToken()->getUser());
    }
}