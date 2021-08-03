<?php
declare(strict_types=1);

namespace App\Infrastructure;

use InvalidArgumentException;
use RuntimeException;
use SplFileInfo;

class FileTypeDetector implements TypeDetectorInterface
{
    public function detectType(string $filePath): string
    {
        if (!file_exists($filePath) || is_dir($filePath)) {
            throw new RuntimeException();
        }

        $fileInfo = new SplFileInfo($filePath);

        switch ($fileInfo->getExtension()) {
            case 'yml':
            case 'yaml':
                return FileTypesEnum::TYPE_YAML;
            case 'json':
                return FileTypesEnum::TYPE_JSON;
            default:
                throw new InvalidArgumentException('unexpected file type');
        }
    }
}