<?php

namespace Beryllium\Icelus;

use Symfony\Component\Filesystem\Filesystem;
use Twig\TwigFunction;

class TwigImageExtensionTest extends IcelusTestBase
{
    public function testValidThumbnail()
    {
        $service = new ImageService($this->source_dir, $this->output_writer, null, new Filesystem, new ImageLoader);

        // test a valid resource
        $thumbnail = $service->thumbnail('valid.jpg', 100, 100, false);
        $this->assertStringContainsString('-100x100.jpeg', $thumbnail);
    }

    public function testInvalidThumbnail()
    {
        $service = new ImageService($this->source_dir, $this->output_writer, null, new Filesystem, new ImageLoader);

        // test an invalid resource
        if (extension_loaded('imagick')) {
            $this->expectException(\ImagickException::class);
            $this->expectExceptionMessageIsOrContains('insufficient image data in file');
        } else if (extension_loaded('gd')) {
            $this->expectException(\RuntimeException::class);
            $this->expectExceptionMessageIsOrContains('Encountered an invalid or unsupported image file');
        } else {
            $this->expectException(\Exception::class);
            $this->expectExceptionMessageIsOrContains('Icelus requires either php-imagick or php-gd');
        }

        $service->thumbnail('invalid.jpg', 100, 100, false);
    }

    public function testNotFoundThumbnail()
    {
        $service = new ImageService($this->source_dir, $this->output_writer, null, new Filesystem, new ImageLoader);

        $this->expectException(ImageNotFoundException::class);
        $this->expectExceptionMessageIsOrContains('Image not found');

        // test a not-found resource
        $service->thumbnail('not-found.jpg', 100, 100, false);
    }

    public function testExtension()
    {
        $service = new ImageService($this->source_dir, $this->output_writer, null, new Filesystem, new ImageLoader);
        $ext = new TwigImageExtension($service);

        $this->assertInstanceOf(TwigImageExtension::class, $ext);
        $this->assertSame('image_extension', $ext->getName());

        $functions = $ext->getFunctions();
        $function = $functions[0] ?? null;

        $this->assertInstanceOf(TwigFunction::class, $function);
        $this->assertStringContainsString('-100x100.jpeg', $function->getCallable()('valid.jpg', 100, 100, false));
    }
}
