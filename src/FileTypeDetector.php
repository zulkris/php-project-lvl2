<?php
declare(strict_types=1);

namespace App;

use InvalidArgumentException;
use RuntimeException;

class FileTypeDetector
{
    public const FILETYPE_YAML = 'yaml';
    public const FILETYPE_JSON = 'json';

    public static function detect(string $fileName): string
    {
        if (!is_file($fileName)) {
            throw new InvalidArgumentException();
        }

        switch ($fileName) {
            case 'yml':
            case 'yaml':
                return self::FILETYPE_YAML;
            case 'json':
                return self::FILETYPE_JSON;
            default:
                throw new RuntimeException('unexpected file type');
        }
    }
}