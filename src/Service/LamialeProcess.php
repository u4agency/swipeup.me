<?php

namespace App\Service;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\StreamInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;

readonly class LamialeProcess
{
    public function __construct(
        private string           $url,
        private string           $path,
        private KernelInterface $appKernel,
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

    public function get($videoFile): Exception|string|GuzzleException|null
    {
        if (!$videoFile) {
            return null;
        }

        $client = new Client();
        try {
            $response = $client->request('POST', $this->getUrl() . $this->getPath(), [
                'multipart' => [
                    [
                        'name' => 'video',
                        'contents' => fopen($videoFile->getRealPath(), 'r'),
                        'filename' => $videoFile->getClientOriginalName()
                    ],
                ],
            ]);
        } catch (GuzzleException $e) {
            return $e;
        }

        if ($response->getStatusCode() !== 200) {
            return null;
        }

        $filename = tempnam($this->appKernel->getProjectDir().'/public/assets/uploaded/swipe_images', 'lamiale');

        file_put_contents($filename, $response->getBody());

        $webmFilename = $filename . '.webm';
        rename($filename, $webmFilename);

        return basename($webmFilename);
    }
}