<?php

namespace App\Twig;

use App\Service\FileTypeService;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class FileTypeExtension extends AbstractExtension
{
    public function __construct(private readonly FileTypeService $fileType)
    {
    }

     public function getFilters(): array
    {
        return [
            new TwigFilter('get_mime_type', [$this, 'getType']),
            new TwigFilter('get_mime', [$this, 'getMime']),
        ];
    }

    public function getType($obj, $name): ?string
    {
        return $this->fileType->getType($obj, $name);
    }

    public function getMime($obj, $name): ?string
    {
        return $this->fileType->getMime($obj, $name);
    }
}