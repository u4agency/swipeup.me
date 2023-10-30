<?php

namespace App\Service;

use App\Entity\Newsletter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Mime\Address;

class NewNewsletter
{
    public function __construct(
        string                 $email,
        string                 $source = "app_register",
        EntityManagerInterface $entityManager,
    )
    {
        $newsletter = new Newsletter();
        $newsletter->setEmail($email);
        $newsletter->setSource($source);

        $entityManager->persist($newsletter);
        try {
            $entityManager->flush();
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}