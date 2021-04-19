<?php
declare(strict_types=1);

namespace App;

class FileConverter
{
    private FileTypeDetector $fileTypeDetector;

    public function __construct(FileTypeDetector $fileTypeDetector)
    {
        $this->fileTypeDetector = $fileTypeDetector;
    }
    public function convert(): string
    {

    }
}