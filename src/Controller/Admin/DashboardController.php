<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Entity\Newsletter;
use App\Entity\Pages;
use App\Entity\Posts;
use App\Entity\Swipe;
use App\Entity\SwipeImage;
use App\Entity\SwipeUp;
use App\Entity\URLShortener;
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

        yield MenuItem::subMenu('SwipeUp')->setSubItems([
            MenuItem::linkToCrud('SwipeUp', 'fas fa-mobile-screen-button', SwipeUp::class),
            MenuItem::linkToCrud('Swipes', 'fas fa-mobile-button', Swipe::class),
            MenuItem::linkToCrud('Fond des Swipes', 'fas fa-image', SwipeImage::class),
        ]);
        yield MenuItem::subMenu('Widgets')->setSubItems([
            MenuItem::linkToCrud('Widgets', 'fab fa-uncharted', Widget::class),
            MenuItem::linkToCrud('Données des widgets', 'fas fa-database', WidgetData::class),
            MenuItem::linkToCrud('Swipes des widgets', 'fas fa-mobile-button', WidgetSwipe::class),
            MenuItem::linkToCrud('Achats des widgets', 'fas fa-credit-card', WidgetUser::class),
        ]);
        yield MenuItem::subMenu('Blog')->setSubItems([
            MenuItem::linkToCrud('Articles', 'fas fa-newspaper', Posts::class),
            MenuItem::linkToCrud('Catégories', 'fas fa-layer-group', Category::class),
            MenuItem::linkToCrud('Pages', 'fas fa-file', Pages::class),
        ]);

        yield MenuItem::linkToCrud('Utilisateurs', 'fas fa-users', User::class);
        yield MenuItem::linkToCrud('Newsletter', 'fas fa-envelope', Newsletter::class);
        yield MenuItem::linkToCrud('URLShortener', 'fas fa-link', URLShortener::class);
    }
}
