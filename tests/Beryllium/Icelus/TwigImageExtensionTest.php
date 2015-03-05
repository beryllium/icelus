<?php

namespace Beryllium\Icelus;

use Imanee\Exception\ImageNotFoundException;
use Imanee\Imanee;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;

class TwigImageExtensionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var string
     */
    public $source_dir;

    /**
     * @var Imanee
     */
    public $imanee;

    /**
     * @var vfsStreamDirectory
     */
    public $output_dir;

    /**
     * @var Twig
     */
    public $twig;

    public function setUp()
    {
        $this->imanee     = new Imanee;
        $this->source_dir = __DIR__ . '/../../Resources';
        $this->output_dir = vfsStream::setup('thumbs');
    }

    public function testValidThumbnail()
    {
        $service = new ImageService($this->imanee, $this->source_dir, $this->output_dir->url('thumbs'), 'test');

        // test a valid resource
        $thumbnail = $service->thumbnail('valid.jpg', 100, 100, false);
        $this->assertContains('-100x100.jpeg', $thumbnail);
    }

    public function testNotFoundThumbnail()
    {
        $service = new ImageService($this->imanee, $this->source_dir, $this->output_dir->url('thumbs'), 'test');

        // test a not-found resource
        try {
            $service->thumbnail('not-found.jpg', 100, 100, false);
        } catch (ImageNotFoundException $e) {}

        $this->assertNotEmpty($e);
    }
}