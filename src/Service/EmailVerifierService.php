<?php

namespace App\Service;


use App\Security\EmailVerifier;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;

readonly class EmailVerifierService
{
    public function __construct(
        private EmailVerifier $emailVerifier
    )
    {
    }

    public function verify($user): void
    {
        $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
            (new TemplatedEmail())
                ->from(MailAddress::minerband())
                ->to($user->getEmail())
                ->subject('Please Confirm your Email')
                ->htmlTemplate('security/confirmation_email.html.twig')
        );
    }
}