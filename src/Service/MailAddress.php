<?php

namespace App\Service;

use Symfony\Component\Mime\Address;

class MailAddress
{
    static public function default(): Address
    {
        return new Address('no-reply@swipeup.me', 'SwipeUp');
    }
}