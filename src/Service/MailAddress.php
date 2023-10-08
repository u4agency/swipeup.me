<?php

namespace App\Service;

use Symfony\Component\Mime\Address;

class MailAddress
{
    static public function minerband(): Address
    {
        return new Address('no-reply@minerband.com', 'minerband');
    }
}