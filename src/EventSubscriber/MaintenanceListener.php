<?php
// src/EventSubscriber/MaintenanceListener.php

namespace App\EventSubscriber;

use App\Utils\MaintenanceMode;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Bundle\SecurityBundle\Security;

class MaintenanceListener implements EventSubscriberInterface
{
    private $router;
    private $maintenanceMode;
    private $security;

    public function __construct(RouterInterface $router, MaintenanceMode $maintenanceMode, Security $security)
    {
        $this->router = $router;
        $this->maintenanceMode = $maintenanceMode;
        $this->security = $security;
    }

    public function onKernelRequest(RequestEvent $event)
    {
        if ($this->maintenanceMode->isMaintenanceMode()) {
            $request = $event->getRequest();
            $path = $request->getPathInfo();

            // Vérifiez si l'utilisateur est un administrateur connecté
            // Implementez ici votre propre logique d'authentification des administrateurs
            $isAdmin = $this->security->isGranted('ROLE_ADMIN');

            $routesAllowed = [
                $this->router->generate('maintenance'),
                $this->router->generate('app_login_form'),
                '/ws', # POUR LA WEB DEBUG TOOLBAR
                '/_profiler', # POUR LA WEB DEBUG TOOLBAR
            ];

//            if (!in_array($path, $routesAllowed) && !$isAdmin) {
//                // Rediriger toutes les URL vers la page de maintenance
//                $response = new RedirectResponse($this->router->generate('maintenance'));
//
//                $event->setResponse($response);
//            }
        }
    }

    public static function getSubscribedEvents(): array
    {
        return [
            RequestEvent::class => 'onKernelRequest',
        ];
    }
}
