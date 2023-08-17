<?php

namespace App\Controller\Admin;

use App\Entity\Newsletter;
use App\Entity\Swipe;
use App\Entity\SwipeImage;
use App\Entity\SwipeUp;
use App\Entity\User;
use App\Entity\Widget;
use App\Entity\WidgetData;
use App\Entity\WidgetSwipe;
use App\Entity\WidgetUser;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    #[Route('/omnip', name: 'admin')]
    public function index(): Response
    {
        // Option 1. You can make your dashboard redirect to some common page of your backend
        //
        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        return $this->redirect($adminUrlGenerator->setController(SwipeImageCrudController::class)->generateUrl());

        // Option 2. You can make your dashboard redirect to different pages depending on the user
        //
        // if ('jane' === $this->getUser()->getUsername()) {
        //     return $this->redirect('...');
        // }

        // Option 3. You can render some custom template to display a proper dashboard with widgets, etc.
        // (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)
        //
        // return $this->render('some/path/my-dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('SwipeUp Admin');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('SwipeUp', 'fas fa-list', SwipeUp::class);
        yield MenuItem::linkToCrud('Sections des SwipeUp', 'fas fa-list', Swipe::class);
        yield MenuItem::linkToCrud('SwipeUp Images', 'fas fa-list', SwipeImage::class);
        yield MenuItem::linkToCrud('Widgets', 'fas fa-list', Widget::class);
        yield MenuItem::linkToCrud('Données des widgets', 'fas fa-list', WidgetData::class);
        yield MenuItem::linkToCrud('Swipes des widgets', 'fas fa-list', WidgetSwipe::class);
        yield MenuItem::linkToCrud('Achats des widgets', 'fas fa-list', WidgetUser::class);
        yield MenuItem::linkToCrud('Utilisateurs', 'fas fa-users', User::class);
        yield MenuItem::linkToCrud('Newsletter', 'fas fa-clipboard', Newsletter::class);
    }
}
