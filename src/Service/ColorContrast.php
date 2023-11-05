<?php

namespace App\Service;

use ColorContrast\ColorContrast as DavidGorges_ColorContrast;
use Symfony\Component\Mime\Address;

class ColorContrast
{
    static public function getBool(string $hex): bool
    {
        return (new DavidGorges_ColorContrast())->complimentaryTheme($hex) == DavidGorges_ColorContrast::LIGHT;
    }
}