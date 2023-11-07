<?php
# src/Security/GoogleAuthenticator.php
namespace App\Security;

use App\Entity\User;
use App\Service\NewNewsletter;
use League\OAuth2\Client\Provider\GoogleUser;
use Doctrine\ORM\EntityManagerInterface;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use KnpU\OAuth2ClientBundle\Security\Authenticator\OAuth2Authenticator;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class GoogleAuthenticator extends OAuth2Authenticator
{
    use TargetPathTrait;

    public function __construct(
        private readonly ClientRegistry         $clientRegistry,
        private readonly EntityManagerInterface $entityManager,
        private readonly RouterInterface        $router,
        private readonly Security               $security,
    )
    {
    }

    public function supports(Request $request): ?bool
    {
        // continue ONLY if the current ROUTE matches the check ROUTE
        return $request->attributes->get('_route') === 'connect_google_check';
    }

    public function authenticate(Request $request): Passport
    {
        $client = $this->clientRegistry->getClient('google');
        $accessToken = $this->fetchAccessToken($client);

        return new SelfValidatingPassport(
            new UserBadge($accessToken->getToken(), function () use ($accessToken, $client) {
                /** @var GoogleUser $googleUser */
                $googleUser = $client->fetchUserFromToken($accessToken);

                // have they logged in with Google before? Easy!
                $withEmail = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $googleUser->getEmail()]);
                $withId = $this->entityManager->getRepository(User::class)->findOneBy(['googleId' => $googleUser->getId()]);
                $existingUser = $withId ?? $withEmail;

                if ($this->security->getUser()) {
                    $existingUser = $this->security->getUser();
                    $existingUser->setGoogleId($googleUser->getId());

                    $this->entityManager->persist($existingUser);
                } else {
                    //User doesnt exist, we create it !
                    if (!$existingUser) {
                        $existingUser = new User();
                        $existingUser->setUsername($googleUser->getName());
                        $existingUser->setEmail($googleUser->getEmail());
                        $existingUser->setGoogleId($googleUser->getId());

                        new NewNewsletter($existingUser->getEmail(), $this->entityManager, "app_register (Google OAuth2)");

                        if ($this->entityManager->isOpen()) {
                            $this->entityManager->persist($existingUser);
                        }
                    }

                    if (!$existingUser->getGoogleId()) {
                        $existingUser->setGoogleId($googleUser->getId());

                        $this->entityManager->persist($existingUser);
                    }
                }

                $this->entityManager->flush();
                $this->entityManager->close();

                return $existingUser;
            })
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        if ($targetPath = $this->getTargetPath($request->getSession(), $firewallName)) {
            return new RedirectResponse($targetPath);
        }

        // change "app_dashboard" to some route in your app
        return new RedirectResponse(
            $this->router->generate('app_user_admin_list')
        );

        // or, on success, let the request continue to be handled by the controller
        //return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        $message = strtr($exception->getMessageKey(), $exception->getMessageData());

        return new Response($message, Response::HTTP_FORBIDDEN);
    }

//    public function start(Request $request, AuthenticationException $authException = null): Response
//    {
//        /*
//         * If you would like this class to control what happens when an anonymous user accesses a
//         * protected page (e.g. redirect to /login), uncomment this method and make this class
//         * implement Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface.
//         *
//         * For more details, see https://symfony.com/doc/current/security/experimental_authenticators.html#configuring-the-authentication-entry-point
//         */
//    }
}