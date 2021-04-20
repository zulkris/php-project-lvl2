<?php
declare(strict_types=1);

namespace App;

use InvalidArgumentException;
use RuntimeException;
use SplFileInfo;

class FileTypeDetector
{
    public const FILETYPE_YAML = 'yaml';
    public const FILETYPE_JSON = 'json';

    public static function detect(string $fileName): string
    {
        if (!file_exists($fileName)) {
            throw new RuntimeException();
        }

        $fileInfo = new SplFileInfo($fileName);

        switch ($fileInfo->getExtension()) {
            case 'yml':
            case 'yaml':
                return self::FILETYPE_YAML;
            case 'json':
                return self::FILETYPE_JSON;
            default:
                throw new InvalidArgumentException('unexpected file type');
        }
    }
}