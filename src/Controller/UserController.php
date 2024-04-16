<?php

namespace App\Controller;

use App\Entity\Swipe;
use App\Entity\SwipeImage;
use App\Entity\SwipeUp;
use App\Entity\WidgetData;
use App\Entity\WidgetSwipe;
use App\Form\SwipeFastType;
use App\Form\SwipeSectionType;
use App\Form\SwipeUpEditType;
use App\Form\UserEditFormType;
use App\Repository\AnalyticsVisitsSwipeUpRepository;
use App\Repository\AnalyticsVisitsURLShortenerRepository;
use App\Repository\NewsletterRepository;
use App\Repository\SwipeUpRepository;
use App\Repository\URLShortenerRepository;
use App\Repository\WidgetRepository;
use App\Service\Status;
use App\Service\UserService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;

#[Route('/admin', name: 'app_user_')]
#[IsGranted('IS_AUTHENTICATED')]
class UserController extends AbstractController
{
    #[Route('/mine', name: 'admin_list')]
    public function adminList(
        SwipeUpRepository $swipeUpRepository,
    ): Response
    {
        return $this->render('user/admin/list.html.twig', [
            'swipeups' => $swipeUpRepository->getAll($this->getUser(), $this->isGranted('ROLE_ADMIN')),
        ]);
    }

    #[Route('/swipeup/@{slug}', name: 'swipeup_edit')]
    public function editSwipeUp(SwipeUp $swipeup): Response
    {
        if ($swipeup->getStatus() === Status::DELETED && !$this->isGranted('ROLE_ADMIN')) {
            $this->addFlash('error', "Ce SwipeUp n'existe pas !");
            return $this->redirectToRoute('app_user_admin_list');
        }

        if ($swipeup->getAuthor() !== $this->getUser() && !$this->isGranted('ROLE_ADMIN')) {
            $this->addFlash('error', "Vous n'êtes pas l'auteur de ce SwipeUp !");
            return $this->redirectToRoute('app_swipeup_single', [
                'slug' => $swipeup->getSlug()
            ]);
        }

        return $this->render('swipe/edit.html.twig', [
            'swipeup' => $swipeup,
            'intro' => $this->getUser()->getSwipeUps()->count() === 1 && $swipeup->getSwipes()->count() <= 1,
        ]);
    }

    #[Route('/swipeup/@{slug}/steps', name: 'swipeup_steps')]
    #[Route('/swipeup/@{slug}/steps/customize', name: 'swipeup_steps_customize')]
    public function stepsCustomizeSwipeUp(
        SwipeUp                $swipeup,
        EntityManagerInterface $entityManager,
        Request                $request,
    ): Response
    {
        if ($swipeup->getStatus() === Status::DELETED && !$this->isGranted('ROLE_ADMIN')) {
            $this->addFlash('error', "Ce SwipeUp n'existe pas !");
            return $this->redirectToRoute('app_user_admin_list');
        }

        if ($swipeup->getAuthor() !== $this->getUser() && !$this->isGranted('ROLE_ADMIN')) {
            $this->addFlash('error', "Vous n'êtes pas l'auteur de ce SwipeUp !");
            return $this->redirectToRoute('app_swipeup_single', [
                'slug' => $swipeup->getSlug()
            ]);
        }

        $form = $this->createForm(SwipeUpEditType::class, $swipeup);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $entityManager->persist($swipeup);
                $entityManager->flush();
            } catch (\Exception $exception) {
                $this->addFlash('error', "Une erreur est survenue lors de la modification du SwipeUp !");
            }

            return $this->redirectToRoute('app_user_swipeup_steps_firstswipe', [
                'slug' => $swipeup->getSlug()
            ]);
        }

        return $this->render('swipe/steps_customize.html.twig', [
            'swipeup' => $swipeup,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/swipeup/@{slug}/steps/first-swipe', name: 'swipeup_steps_firstswipe')]
    public function stepsFirstSwipeSwipeUp(
        SwipeUp                $swipeup,
        EntityManagerInterface $entityManager,
        Request                $request,
        WidgetRepository       $widgetRepository,
    ): Response
    {
        if ($swipeup->getStatus() === Status::DELETED && !$this->isGranted('ROLE_ADMIN')) {
            $this->addFlash('error', "Ce SwipeUp n'existe pas !");
            return $this->redirectToRoute('app_user_admin_list');
        }

        if ($swipeup->getAuthor() !== $this->getUser() && !$this->isGranted('ROLE_ADMIN')) {
            $this->addFlash('error', "Vous n'êtes pas l'auteur de ce SwipeUp !");
            return $this->redirectToRoute('app_swipeup_single', [
                'slug' => $swipeup->getSlug()
            ]);
        }

        $form = $this->createForm(SwipeFastType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $textWidget = $widgetRepository->findOneBy(['name' => 'text']);
                $buttonWidget = $widgetRepository->findOneBy(['name' => 'button']);

                $background = $form->get('background')->getData();

                $swipeImage = new SwipeImage();
                $swipeImage->setBackgroundFile($background);
                $swipeImage->setBackgroundName($background);
                $swipeImage->setAuthor($this->getUser());


                $swipeBodyData = new WidgetData();
                $swipeBodyData->setDataName('text');
                $swipeBodyData->setDataValue('Rejoignez-moi sur ' . $form->get('text')->getData() . ' !');
                $swipeBodyData->setWidget($textWidget);

                $swipeBody = new WidgetSwipe();
                $swipeBody->setWidget($textWidget);
                $swipeBody->addWidgetData($swipeBodyData);

                $swipeFooterDataText = new WidgetData();
                $swipeFooterDataText->setDataName('text');
                $swipeFooterDataText->setDataValue($form->get('text')->getData());
                $swipeFooterDataText->setWidget($buttonWidget);
                $swipeFooterDataHref = new WidgetData();
                $swipeFooterDataHref->setDataName('href');
                $swipeFooterDataHref->setDataValue($form->get('link')->getData());
                $swipeFooterDataHref->setWidget($buttonWidget);
                $swipeFooterDataColorType = new WidgetData();
                $swipeFooterDataColorType->setDataName('colorType');
                $swipeFooterDataColorType->setDataValue('simple');
                $swipeFooterDataColorType->setWidget($buttonWidget);
                $swipeFooterDataColor = new WidgetData();
                $swipeFooterDataColor->setDataName('primaryColor');
                $swipeFooterDataColor->setDataValue('#' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT));
                $swipeFooterDataColor->setWidget($buttonWidget);

                $swipeFooter = new WidgetSwipe();
                $swipeFooter->setWidget($buttonWidget);
                $swipeFooter->addWidgetData($swipeFooterDataText);
                $swipeFooter->addWidgetData($swipeFooterDataHref);
                $swipeFooter->addWidgetData($swipeFooterDataColorType);
                $swipeFooter->addWidgetData($swipeFooterDataColor);

                $swipe = new Swipe();
                $swipe->setSwipeUp($swipeup);
                $swipe->setBackground($swipeImage);
                $swipe->setSequence(0);
                $swipe->setWidgetBody($swipeBody);
                $swipe->setWidgetFooter($swipeFooter);


                $entityManager->persist($swipeImage);

                $entityManager->persist($swipeBody);
                $entityManager->persist($swipeBodyData);

                $entityManager->persist($swipeFooter);
                $entityManager->persist($swipeFooterDataText);
                $entityManager->persist($swipeFooterDataHref);
                $entityManager->persist($swipeFooterDataColorType);
                $entityManager->persist($swipeFooterDataColor);

                $entityManager->persist($swipe);

                $entityManager->persist($swipeup);
                $entityManager->flush();
            } catch (\Exception $exception) {
                throw $exception;
                $this->addFlash('error', "Une erreur est survenue lors de la modification du SwipeUp !");
            }

            return $this->redirectToRoute('app_user_swipeup_edit', [
                'slug' => $swipeup->getSlug()
            ]);
        }

        return $this->render('swipe/steps_firstswipe.html.twig', [
            'swipeup' => $swipeup,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/swipeup/@{slug}/settings', name: 'swipeup_settings')]
    public function settingsSwipeUp(
        SwipeUp                $swipeup,
        EntityManagerInterface $entityManager,
        Request                $request,
    ): Response
    {
        if ($swipeup->getStatus() === Status::DELETED && !$this->isGranted('ROLE_ADMIN')) {
            $this->addFlash('error', "Ce SwipeUp n'existe pas !");
            return $this->redirectToRoute('app_user_admin_list');
        }

        if ($swipeup->getAuthor() !== $this->getUser() && !$this->isGranted('ROLE_ADMIN')) {
            $this->addFlash('error', "Vous n'êtes pas l'auteur de ce SwipeUp !");
            return $this->redirectToRoute('app_swipeup_single', [
                'slug' => $swipeup->getSlug()
            ]);
        }

        $form = $this->createForm(SwipeUpEditType::class, $swipeup);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $slugger = new AsciiSlugger();
                $swipeup->setSlug($slugger->slug($swipeup->getSlug()));

                $entityManager->persist($swipeup);
                $entityManager->flush();
            } catch (\Exception $exception) {
                $this->addFlash('error', "Une erreur est survenue lors de la modification du SwipeUp !");
            }

            return $this->redirectToRoute('app_user_swipeup_edit', [
                'slug' => $swipeup->getSlug()
            ]);
        }

        return $this->render('swipe/settings.html.twig', [
            'swipeup' => $swipeup,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/analytics/{slug}', name: 'swipeup_analytics', requirements: ['slugs' => '[@~].*'], defaults: ['slug' => ''])]
    public function analyticsSwipeUp(
        string                                $slug,
        SwipeUpRepository                     $swipeUpRepository,
        URLShortenerRepository                $URLShortenerRepository,
        AnalyticsVisitsSwipeUpRepository      $analyticsVisitsSwipeUpRepository,
        AnalyticsVisitsURLShortenerRepository $analyticsVisitsURLShortenerRepository,
        ChartBuilderInterface                 $chartBuilder,
    ): Response
    {
        $swipeup = null;

        if (!empty($slug)) {
            $firstLetter = substr($slug, 0, 1);
            $slug = substr($slug, 1);

            $swipeup = match ($firstLetter) {
                '@' => $swipeUpRepository->findOneBy(['slug' => $slug]),
                '~' => $URLShortenerRepository->findOneBy(['slug' => $slug]),
                default => null,
            };

            if (!$swipeup || ($firstLetter === '@' ? $swipeup->getStatus() === Status::DELETED : null && !$this->isGranted('ROLE_ADMIN'))) {
                $this->addFlash('error', "Ce SwipeUp n'existe pas !");
                return $this->redirectToRoute('app_user_admin_list');
            }

            if ($swipeup->getAuthor() !== $this->getUser() && !$this->isGranted('ROLE_ADMIN')) {
                $this->addFlash('error', "Vous n'êtes pas l'auteur de ce SwipeUp !");
                return $this->redirectToRoute('app_swipeup_single', [
                    'slug' => $swipeup->getSlug()
                ]);
            }
        }

        $endDate = new \DateTimeImmutable();
        $startDate = (clone $endDate)->modify('-1 week');

        if (!empty($slug)) {
            $stats = match ($firstLetter) {
                '@' => $analyticsVisitsSwipeUpRepository->findByDateBetween($swipeup, $startDate, $endDate),
                '~' => $analyticsVisitsURLShortenerRepository->findByDateBetween($swipeup, $startDate, $endDate),
                default => [],
            };
        } else {
            $stats = $analyticsVisitsSwipeUpRepository->findByUser($this->getUser(), $startDate, $endDate);
        }
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
        $dataVisits = array_column(array_values($completeDailyCounts), 'total');
        $dataOnceUser = array_column(array_values($completeDailyCounts), 'onceUser');

        $visits = $chartBuilder->createChart(Chart::TYPE_LINE);

        $visits->setData([
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Visites les 7 derniers jours',
                    'borderColor' => '#11a6ea',
                    'borderWidth' => 4,
                    'data' => $dataVisits,
                    'tension' => .3,
                ],
                [
                    'label' => 'Visiteurs les 7 derniers jours',
                    'borderColor' => '#11a6ea',
                    'borderDash' => [5, 5],
                    'borderDashOffset' => 2,
                    'borderWidth' => 4,
                    'data' => $dataOnceUser,
                    'tension' => .3,
                ],
            ],
        ]);

        $visits->setOptions([
            'maintainAspectRatio' => false,
            'plugins' => [
                'legend' => [
                    'display' => false,
                ],
            ],
        ]);

        $browsers = $browsersColor = [];

        foreach ($stats as $entity) {
            $userService = new UserService($entity->getUserAgent());
            $browser = $userService->getBrowser();
            $browsers[] = $browser;
        }
        sort($browsers);

        foreach (array_unique($browsers) as $browser) {
            $userService = new UserService();
            $browsersColor[] = $userService->getBrowserColor($browser);
        }

        $browserChart = $chartBuilder->createChart(Chart::TYPE_PIE);

        $browserChart->setData([
            'labels' => array_keys(array_count_values($browsers)),
            'datasets' => [
                [
                    'label' => 'Navigateurs',
                    'backgroundColor' => $browsersColor,
                    'data' => array_values(array_count_values($browsers)),
                    'tension' => .3,
                ],
            ],
        ]);

        $browserChart->setOptions([
            'maintainAspectRatio' => false,
            'plugins' => [
                'legend' => [
                    'display' => false,
                ],
            ],
        ]);

        $allOs = $osColor = [];

        foreach ($stats as $entity) {
            $userService = new UserService($entity->getUserAgent());
            $os = $userService->getOS();
            $allOs[] = $os;
        }
        sort($browsers);

        foreach (array_unique($allOs) as $os) {
            $userService = new UserService();
            $osColor[] = $userService->getBrowserColor($os);
        }

        $osChart = $chartBuilder->createChart(Chart::TYPE_PIE);

        $osChart->setData([
            'labels' => array_keys(array_count_values($allOs)),
            'datasets' => [
                [
                    'label' => 'Systèmes d\'exploitation',
                    'backgroundColor' => $osColor,
                    'data' => array_values(array_count_values($allOs)),
                    'tension' => .3,
                ],
            ],
        ]);

        $osChart->setOptions([
            'maintainAspectRatio' => false,
            'plugins' => [
                'legend' => [
                    'display' => false,
                ],
            ],
        ]);

        return $this->render('swipe/analytics.html.twig', [
            'swipeup' => $swipeup,
            'stats' => $stats,
            'visits' => $visits,
            'browsers' => $browserChart,
            'os' => $osChart,
        ]);
    }

    #[Route('/profile/edit', name: 'profile_edit')]
    public function profileEdit(
        Request                $request,
        EntityManagerInterface $entityManager,
        NewsletterRepository   $newsletterRepository,
    ): Response
    {
        $form = $this->createForm(UserEditFormType::class, $this->getUser(), [
            'newsletter' => $newsletterRepository->findOneBy(['email' => $this->getUser()->getEmail()]),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $entityManager->flush();
                $this->addFlash('success', "Votre profil a bien été modifié !");
            } catch (\Exception $exception) {
                $this->addFlash('error', "Une erreur est survenue lors de la modification de votre profil !");
            }

            return $this->redirectToRoute('app_user_profile_edit');
        }

        return $this->render('user/profile/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
