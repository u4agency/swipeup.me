<?php

namespace App\Twig;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;

class FileTypeExtension extends AbstractExtension
{
    private UploaderHelper $uploaderHelper;
    private ParameterBagInterface $parameterBag;

    public function __construct(UploaderHelper $uploaderHelper, ParameterBagInterface $parameterBag)
    {
        $this->uploaderHelper = $uploaderHelper;
        $this->parameterBag = $parameterBag;
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
        if ($obj !== null) {
            (string)$mime = mime_content_type($this->parameterBag->get('kernel.project_dir') . '/public' . $this->uploaderHelper->asset($obj, $name));
            return explode('/', $mime)[0];
        } else {
            return null;
        }
    }

    public function getMime($obj, $name): ?string
    {
        if ($obj !== null) {
            return (string)mime_content_type($this->parameterBag->get('kernel.project_dir') . '/public' . $this->uploaderHelper->asset($obj, $name));
        } else {
            return null;
        }
    }
}