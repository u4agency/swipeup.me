<?php

namespace App\Controller;

use App\Entity\Newsletter;
use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Security\AppCustomAuthenticator;
use App\Security\EmailVerifier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Symfony\Component\Security\Http\Util\TargetPathTrait;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

class RegistrationController extends AbstractController
{
    use TargetPathTrait;

    public function __construct(private readonly EmailVerifier $emailVerifier)
    {
    }

    public function sendVerifyEmail(User $user)
    {
        // generate a signed url and email it to the user
        $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
            (new TemplatedEmail())
                ->from(new Address('no-reply@swipeup.me', 'SwipeUp Team'))
                ->to($user->getEmail())
                ->subject('Please Confirm your Email')
                ->htmlTemplate('security/confirmation_email.html.twig')
        );
    }

    #[Route('/verify/sendEmail', name: 'app_verify_send_email')]
    public function verifySendEmail(): RedirectResponse
    {
        if (
            !$this->getUser() ||
            $this->getUser() &&
            $this->getUser()->isVerified() === true &&
            $this->isGranted('IS_AUTHENTICATED_FULLY')
        ) {
            return $this->redirectToRoute('app_homepage');
        }

        $this->sendVerifyEmail($this->getUser());

        $this->addFlash('success', 'Un nouveau mail de vérification a été envoyé');

        return $this->redirectToRoute('app_verify');
    }

    #[Route('/register', name: 'app_register')]
    public function register(
        Request                     $request,
        UserPasswordHasherInterface $userPasswordHasher,
        UserAuthenticatorInterface  $userAuthenticator,
        AppCustomAuthenticator      $authenticator,
        EntityManagerInterface      $entityManager,
    ): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('app_homepage');
        }

        if ($request->query->has('swipeup_create')) {
            $this->saveTargetPath($request->getSession(), 'main', $this->generateUrl('app_swipe_create', ['slug' => $request->query->get('swipeup_create')]));
        }

        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('password')->getData()
                )
            );

            $newsletter = new Newsletter();
            $newsletter->setSource($request->attributes->get('_route'));
            $newsletter->setEmail($user->getEmail());

            $entityManager->persist($user);
            $entityManager->persist($newsletter);
            $entityManager->flush();

            $this->sendVerifyEmail($user);

            return $userAuthenticator->authenticateUser(
                $user,
                $authenticator,
                $request
            );
        }

        return $this->render('security/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route('/verify', name: 'app_verify')]
    public function verifyUser(): Response
    {
        if (
            !$this->getUser() ||
            $this->getUser() &&
            $this->getUser()->isVerified() === true &&
            $this->isGranted('IS_AUTHENTICATED_FULLY')
        ) {
            return $this->redirectToRoute('app_homepage');
        }

        return $this->render('security/verify.html.twig');
    }

    #[Route('/verify/email', name: 'app_verify_email')]
    public function verifyUserEmail(Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        try {
            $this->emailVerifier->handleEmailConfirmation($request, $this->getUser());
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('error', 'Le lien pour vérifier votre compte est invalide');
            return $this->redirectToRoute('app_register');
        }

        $targetPath = $this->getTargetPath($request->getSession(), 'main');
        $this->addFlash('success', 'Votre compte SwipeUp a été validé !');

        return $targetPath !== null ? $this->redirect($targetPath) : $this->redirectToRoute('app_homepage');
    }
}
