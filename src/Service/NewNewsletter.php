<?php

namespace App\Service;

use App\Entity\Newsletter;
use Doctrine\ORM\EntityManagerInterface;

class NewNewsletter
{
    public function __construct(
        string                 $email,
        EntityManagerInterface $entityManager,
        string                 $source = "app_register",
    )
    {
        $newsletter = new Newsletter();
        $newsletter->setEmail($email);
        $newsletter->setSource($source);

        $entityManager->persist($newsletter);
        try {
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}