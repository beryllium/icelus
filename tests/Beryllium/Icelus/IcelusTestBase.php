<?php

namespace Beryllium\Icelus;

use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;

class IcelusTestBase extends TestCase
{
    public string $source_dir;
    public string $output_dir;
    public $output_writer;

    public function setUp(): void
    {
        $this->source_dir = __DIR__ . '/../../Resources';
        $this->output_dir = vfsStream::setup('thumbs')->url();

        $this->output_writer = new class($this->output_dir) {
            public $output_dir;

            public function __construct($outputDir) {
                $this->output_dir = $outputDir;
            }

            public function setOutputDir($dir): void {
                $this->output_dir = $dir;
            }

            public function getOutputDir(): string {
                return $this->output_dir;
            }
        };
    }
}
