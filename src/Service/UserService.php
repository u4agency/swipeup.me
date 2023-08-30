<?php

namespace App\Service;

use WhichBrowser\Parser;

class UserService
{
    private Parser $result;

    public function __construct(?string $userAgent = null)
    {
        $this->result = new Parser($userAgent);
    }

    public function getBrowser(): string
    {
        return $this->result->browser->getName();
    }

    public function getOS(): string
    {
        return $this->result->os->getName();
    }

    public function getBrowserColor($string): string
    {
        $color = "#11a6ea";

        if ($string === "Mozilla Firefox") {
            $color = "#f75732";
        } elseif ($string === "Google Chrome") {
            $color = "#2ca44d";
        } elseif ($string === "Apple Safari") {
            $color = "#0cb1e4";
        } elseif ($string === "Microsoft Edge") {
            $color = "#0e5197";
        } elseif ($string === "Opera") {
            $color = "#f21d4c";
        } elseif ($string === "Internet Explorer") {
            $color = "#f5c52d";
        }

        return $color;
    }
}