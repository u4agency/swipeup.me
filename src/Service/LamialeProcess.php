<?php

namespace App\Service;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Random\RandomException;
use Symfony\Component\HttpKernel\KernelInterface;
use ZipArchive;
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
    public function get($videoFile): Exception|array|GuzzleException|null
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

        $backgroundPath = $this->appKernel->getProjectDir() . '/public/assets/uploaded/swipe_images/';
        $thumbnailPath = $this->appKernel->getProjectDir() . '/public/assets/uploaded/swipe_thumbnails/';
        $tempPath = $this->appKernel->getProjectDir() . '/public/assets/uploaded/tmp/';

        $name = hash('sha256', microtime(true) . random_int(0, 9_999_999));
        if (null !== 50) $name = substr($name, 0, 50);

        $zipPath = $this->appKernel->getProjectDir() . "/public/assets/uploaded/tmp/$name.zip";
        file_put_contents($zipPath, $response->getBody());

        $zip = new ZipArchive();
        if ($zip->open($zipPath) === TRUE) {
            $zip->extractTo($tempPath);
            $zip->close();
        } else {
            return null;
        }

        unlink($zipPath); // Delete the ZIP file

        $videoExt = 'mp4'; // Assuming video is mp4
        $imageExt = 'webp'; // Assuming image is webp

        $videoPath = $tempPath . 'processed_video.mp4';
        $imagePath = $tempPath . 'thumbnail.webp';

        $newVideoPath = $backgroundPath . $name . '.' . $videoExt;
        $newImagePath = $thumbnailPath . $name . '.' . $imageExt;

        if (file_exists($videoPath)) {
            rename($videoPath, $newVideoPath);
        } else {
            return null;
        }

        if (file_exists($imagePath)) {
            rename($imagePath, $newImagePath);
        } else {
            return null;
        }

        return [
            'background' => basename($newVideoPath),
            'thumbnail' => basename($newImagePath)
        ];
    }
}