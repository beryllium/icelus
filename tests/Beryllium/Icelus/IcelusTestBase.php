<?php

namespace Beryllium\Icelus;

use Imanee\Imanee;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;

class IcelusTestBase extends \PHPUnit\Framework\TestCase
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

    public $output_writer;

    public function setUp()
    {
        $this->imanee     = new Imanee;
        $this->source_dir = __DIR__ . '/../../Resources';
        $this->output_dir = vfsStream::setup('thumbs')->url();

        $this->output_writer = new class($this->output_dir) {
            public $output_dir;

            public function __construct($outputDir) {
                $this->output_dir = $outputDir;
            }

            public function setOutputDir($dir) {
                $this->output_dir = $dir;
            }

            public function getOutputDir() {
                return $this->output_dir;
            }
        };
    }
}
