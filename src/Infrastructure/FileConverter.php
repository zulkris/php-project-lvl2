<?php
declare(strict_types=1);

namespace App\Infrastructure;

use App\Domain\FileConverterInterface;
use RuntimeException;
use function json_decode;

class FileConverter implements FileConverterInterface
{
    private TypeDetectorInterface $fileTypeDetector;

    public function __construct(TypeDetectorInterface $fileTypeDetector)
    {
        $this->fileTypeDetector = $fileTypeDetector;
    }

    public function convert($file): array
    {
        $fileType = $this->fileTypeDetector->detectType($file);

        $fileContent = file_get_contents($file);

        switch ($fileType) {
            case FileTypesEnum::TYPE_JSON:
                return json_decode($fileContent, true, 512, JSON_THROW_ON_ERROR);
            default:
                throw new RuntimeException('unbelievable case!');
        }

    }
}