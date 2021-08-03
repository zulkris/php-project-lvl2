<?php
declare(strict_types=1);

namespace App\Domain;

interface FileConverterInterface
{
    public function convert($file): array;
}