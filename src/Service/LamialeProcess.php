<?php

namespace App\Service;

readonly class LamialeProcess
{
    public function __construct(
        private string $url,
        private string $path,
    )
    {
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getPath(): string
    {
        return $this->path;
    }
}