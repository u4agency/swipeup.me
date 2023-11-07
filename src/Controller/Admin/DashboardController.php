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
use App\Repository\AnalyticsVisitsSwipeRepository;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;

class DashboardController extends AbstractDashboardController
{
    public function __construct(
        private readonly ChartBuilderInterface          $chartBuilder,
        private readonly AnalyticsVisitsSwipeRepository $analyticsVisitsSwipeRepository,
    )
    {
    }

    #[Route('/omnip', name: 'admin')]
    public function index(): Response
    {
        $days = 30;

        $endDate = new \DateTimeImmutable();
        $startDate = (clone $endDate)->modify("-$days days");

        $stats = $this->analyticsVisitsSwipeRepository->findByDateBetween($startDate, $endDate);

        $dailyCounts = $users = [];

        foreach ($stats as $entity) {
            $day = $entity->getVisitedAt()->format('Y-m-d');

            if (!isset($dailyCounts[$day])) {
                $dailyCounts[$day] = ['total' => 0, 'onceUser' => 0];
            }

            $dailyCounts[$day]['total']++;

            if (!in_array($entity->getUserId(), $users)) {
                $dailyCounts[$day]['onceUser']++;
                $users[] = $entity->getUserId();
            }
        }

        $period = new \DatePeriod($startDate, new \DateInterval('P1D'), $endDate);
        $completeDailyCounts = array_fill_keys(array_map(fn($day) => $day->format('Y-m-d'), iterator_to_array($period)), ['total' => 0, 'onceUser' => 0]);

        foreach ($dailyCounts as $day => $count) {
            $completeDailyCounts[$day] = $count;
        }

        $labels = array_keys($completeDailyCounts);
        $dataSwipes = array_column(array_values($completeDailyCounts), 'total');

        $sum = 0;
        $dataSwipesTotal = [];
        foreach ($dataSwipes as $key => $value) {
            $sum += $value;
            $dataSwipesTotal[$key] = $sum;
        }

        $visits = $this->chartBuilder->createChart(Chart::TYPE_LINE);

        $visits->setData([
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => " ",
                    'borderColor' => '#11a6ea',
                    'borderWidth' => 4,
                    'data' => $dataSwipes,
                    'tension' => .3,
                ],
            ],
        ]);

        $visits->setOptions([
            'responsive' => true,
            'maintainAspectRatio' => false,
            'plugins' => [
                'legend' => [
                    'display' => false,
                ],
                'title' => [
                    'display' => true,
                    'text' => "Swipes les $days derniers jours",
                ],
            ],
        ]);

        $visitsTotal = $this->chartBuilder->createChart(Chart::TYPE_LINE);

        $visitsTotal->setData([
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => " ",
                    'borderColor' => '#11a6ea',
                    'borderWidth' => 4,
                    'data' => $dataSwipesTotal,
                    'tension' => .3,
                ],
            ],
        ]);

        $visitsTotal->setOptions([
            'responsive' => true,
            'maintainAspectRatio' => false,
            'plugins' => [
                'legend' => [
                    'display' => false,
                ],
                'title' => [
                    'display' => true,
                    'text' => "Total des swipes les $days derniers jours",
                ],
            ],
        ]);

        return $this->render('admin/my-dashboard.html.twig', [
            'visits' => $visits,
            'visitsTotal' => $visitsTotal,
        ]);
    }

    public function configureAssets(): Assets
    {
        return Assets::new()
            ->addWebpackEncoreEntry('app');
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
