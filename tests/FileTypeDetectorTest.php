<?php
declare(strict_types=1);

use App\Infrastructure\FileTypeDetector;
use App\Infrastructure\FileTypesEnum;
use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;

class FileTypeDetectorTest extends TestCase
{
    private FileTypeDetector $fileTypeDetector;

    protected function setUp(): void
    {
        $this->fileTypeDetector = new FileTypeDetector();
    }

    /** @test */
    public function fileNotExistsThrowsError(): void
    {
        $this->expectException(RuntimeException::class);
        $this->fileTypeDetector->detectType('randomFileName');
    }

    /** @test */
    public function unknownFileNameThrowsError(): void
    {
        $root     = vfsStream::setup();
        $jsonFile = vfsStream::newFile('somefile.opa')->at($root);

        $this->expectException(InvalidArgumentException::class);
        $this->fileTypeDetector->detectType($jsonFile->url());
    }

    /** @test */
    public function correctFileNameReturnsCorrectFileType()
    {
        $root     = vfsStream::setup();
        $jsonFile = vfsStream::newFile('somefile.json')->at($root);

        self::assertEquals(FileTypesEnum::TYPE_JSON, $this->fileTypeDetector->detectType($jsonFile->url()));
    }
}