<?php

namespace Beryllium\Icelus;

use Imanee\Exception\ImageNotFoundException;
use Symfony\Component\Filesystem\Filesystem;
use Twig\TwigFunction;

class TwigImageExtensionTest extends IcelusTestBase
{
    public function testValidThumbnail()
    {
        $service = new ImageService($this->imanee, $this->source_dir, $this->output_writer, null, new Filesystem);

        // test a valid resource
        $thumbnail = $service->thumbnail('valid.jpg', 100, 100, false);
        $this->assertStringContainsString('-100x100.jpeg', $thumbnail);
    }

    public function testNotFoundThumbnail()
    {
        $service = new ImageService($this->imanee, $this->source_dir, $this->output_writer, null, new Filesystem);

        // test a not-found resource
        try {
            $service->thumbnail('not-found.jpg', 100, 100, false);
        } catch (ImageNotFoundException $e) {}

        $this->assertNotEmpty($e);
    }

    public function testExtension()
    {
        $service = new ImageService($this->imanee, $this->source_dir, $this->output_writer, null, new Filesystem);
        $ext = new TwigImageExtension($service);

        $this->assertInstanceOf(TwigImageExtension::class, $ext);
        $this->assertSame('image_extension', $ext->getName());

        $functions = $ext->getFunctions();
        $function = $functions[0] ?? null;

        $this->assertInstanceOf(TwigFunction::class, $function);
        $this->assertStringContainsString('-100x100.jpeg', $function->getCallable()('valid.jpg', 100, 100, false));
    }
}
