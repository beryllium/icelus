<?php

namespace Beryllium\Icelus;

use Symfony\Component\Filesystem\Filesystem;

class ImageServiceTest extends IcelusTestBase
{
    public function testValidThumbnail()
    {
        $service = new ImageService(
            $this->source_dir,
            $this->output_writer,
            null,
            new Filesystem,
        );

        // test a valid resource
        $thumbnail = $service->thumbnail('valid.jpg', 100, 100, false);
        $this->assertStringContainsString('-100x100.jpeg', $thumbnail);
    }

    public function testNotFoundThumbnail()
    {
        $service = new ImageService(
            $this->source_dir,
            $this->output_writer,
            null,
            new Filesystem
        );

        $this->expectException(\ImagickException::class);
        $this->expectExceptionCode(435);
        $this->expectExceptionMessageIsOrContains('unable to open image');

        // test a not-found resource
        $service->thumbnail('not-found.jpg', 100, 100, false);
    }

    public function testOutputDirNotFound()
    {
        $writer = clone $this->output_writer;
        $writer->setOutputDir($writer->getOutputDir() . '/test');

        $service = new ImageService(
            $this->source_dir,
            $writer,
            null,
            new Filesystem
        );

        // test a valid resource
        $thumbnail = $service->thumbnail('valid.jpg', 100, 100, false);
        $this->assertStringContainsString('-100x100.jpeg', $thumbnail);
    }
}
