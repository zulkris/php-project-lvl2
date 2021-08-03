<?php
declare(strict_types=1);

namespace App\Infrastructure;

interface TypeDetectorInterface
{
    public function detectType(string $filePath): string;
}