<?php

namespace App\Service;

use chillerlan\QRCode\QRCode;

class QRCodeGenerator
{
    static public function write(string $link)
    {
        return (new QRCode)->render($link);
    }
}