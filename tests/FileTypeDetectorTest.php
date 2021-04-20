<?php
declare(strict_types=1);

use App\FileTypeDetector;
use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;

class FileTypeDetectorTest extends TestCase
{
    /** @test */
    public function fileNotExistsThrowsError(): void
    {
        $this->expectException(RuntimeException::class);
        FileTypeDetector::detect('randomFileName');
    }

    /** @test */
    public function unknownFileNameThrowsError(): void
    {
        $root     = vfsStream::setup();
        $jsonFile = vfsStream::newFile('somefile.opa')->at($root);

        $this->expectException(InvalidArgumentException::class);
        FileTypeDetector::detect($jsonFile->url());
    }

    /** @test */
    public function correctFileNameReturnsCorrectFileType()
    {
        $root     = vfsStream::setup();
        $jsonFile = vfsStream::newFile('somefile.json')->at($root);

        self::assertEquals(FileTypeDetector::FILETYPE_JSON, FileTypeDetector::detect($jsonFile->url()));
    }
}