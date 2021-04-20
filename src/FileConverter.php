<?php
declare(strict_types=1);

namespace App;

class FileConverter
{
    public function __construct()
    {

    }

    public function convert($file): string
    {
        $fileType = FileTypeDetector::detect($file);

        $fileContent = file_get_contents($file);

        switch ($fileType) {
            case FileTypeDetector::FILETYPE_JSON:
                return \json_decode($fileContent, true, 512, JSON_THROW_ON_ERROR);
            default:
                throw new \RuntimeException('unbelievable case!');
        }

    }
}