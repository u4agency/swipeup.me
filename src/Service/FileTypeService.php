<?php

namespace App\Service;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;

class FileTypeService
{
    public static array $videoMimeTypes = [
        'video/mp4',                    // .mp4
        'video/quicktime',              // .mov
        'video/x-msvideo',              // .avi
        'video/x-ms-wmv',               // .wmv
        'video/mpeg',                   // .mpeg, .mpg
        'video/ogg',                    // .ogv
        'video/webm',                   // .webm
        'video/3gpp',                   // .3gp
        'video/3gpp2',                  // .3g2
        'video/x-matroska',             // .mkv
        'video/x-flv',                  // .flv
        'video/vnd.dlna.mpeg-tts',      // .ts
        'video/MP2T',                   // .ts
        'video/x-ms-asf',               // .asf, .wma, .wmv
        'application/mp4',              // .mp4
        'video/x-m4v',                  // .m4v
        'video/x-f4v',                  // .f4v
        'video/x-ms-asx',               // .asx
        'video/x-ms-wmx',               // .wmx
        'video/x-ms-wvx',               // .wvx
        'video/x-msvideo',               // .avi

        // Types MIME audio utilisés dans les fichiers vidéo
        'audio/mp4',                    // .mp4, .m4a
        'audio/webm',                   // .webm
        'audio/ogg',                    // .oga, .ogg
        'audio/mpeg',                   // .mp3
        'audio/x-wav',                  // .wav
        'audio/x-aac',                  // .aac
        'audio/x-m4a',                  // .m4a
        'audio/x-ms-wma',               // .wma
        'audio/x-ms-asf',                // .asf
    ];

    public static array $imageMimeTypes = [
        'image/jpeg',               // .jpeg, .jpg
        'image/png',                // .png
        'image/gif',                // .gif
        'image/webp',               // .webp
        'image/tiff',               // .tiff, .tif
        'image/bmp',                // .bmp
        'image/vnd.microsoft.icon', // .ico
        'image/svg+xml',            // .svg
        'image/x-icon',             // .ico
        'image/heic',               // .heic (High Efficiency Image Coding)
        'image/heif',               // .heif (High Efficiency Image File Format)
        'image/avif',               // .avif (AV1 Image File Format)
        'image/apng',               // .apng (Animated Portable Network Graphics)
        'image/jp2',                // .jp2 (JPEG 2000)
        'image/jxr',                // .jxr (JPEG XR)
        'image/psd',                // .psd (Photoshop Document)
        'image/raw',                // RAW image formats
        'image/x-raw',              // Alternative for RAW image formats
        'application/octet-stream',     // Générique pour les fichiers binaires
    ];

    private static UploaderHelper $uploaderHelper;
    private static ParameterBagInterface $parameterBag;

    public function __construct(UploaderHelper $uploaderHelper, ParameterBagInterface $parameterBag)
    {
        self::$uploaderHelper = $uploaderHelper;
        self::$parameterBag = $parameterBag;
    }

    static public function getType($obj, $name): ?string
    {
        if ($obj->getBackgroundName() !== null && self::$uploaderHelper->asset($obj, $name)) {
            try {
                (string)$mime = mime_content_type(self::$parameterBag->get('kernel.project_dir') . '/public' . self::$uploaderHelper->asset($obj, $name));
                return explode('/', $mime)[0];
            } catch (\Exception $e) {
                return null;
            }
        } else {
            return null;
        }
    }

    static public function getMime($obj, $name): ?string
    {
        if ($obj->getBackgroundName() !== null && self::$uploaderHelper->asset($obj, $name)) {
            try {
                return (string)mime_content_type(self::$parameterBag->get('kernel.project_dir') . '/public' . self::$uploaderHelper->asset($obj, $name));
            } catch (\Exception $e) {
                return null;
            }
        } else {
            return null;
        }
    }
}