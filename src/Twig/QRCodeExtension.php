<?php

namespace App\Twig;

use App\Service\QRCodeGenerator;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class QRCodeExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('qr', [$this, 'qr_writer']),
        ];
    }

    public function qr_writer($link)
    {
        return QRCodeGenerator::write($link);
    }
}