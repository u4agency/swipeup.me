<?php

namespace App\Service;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Random\RandomException;
use Symfony\Component\HttpKernel\KernelInterface;
use function hash;
use function microtime;
use function random_int;
use function sprintf;
use function substr;

readonly class LamialeProcess
{
    public function __construct(
        private string          $url,
        private string          $path,
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

    /**
     * @throws RandomException
     */
    public function get($videoFile): Exception|string|GuzzleException|null
    {
        if (!$videoFile) return null;

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

        if ($response->getStatusCode() !== 200) return null;

        $path = $this->appKernel->getProjectDir() . '/public/assets/uploaded/swipe_images/';

        $name = hash('sha256', microtime(true) . random_int(0, 9_999_999));

        if (null !== 50) $name = substr($name, 0, 50);
        if ($extension = $videoFile->guessExtension()) $name = sprintf('%s.%s', $name, $extension);

        $fullPath = $path . $name;
        file_put_contents($fullPath, $response->getBody());

        return basename($fullPath);
    }
}