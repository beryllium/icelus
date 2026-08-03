<?php

namespace Beryllium\Icelus;

use PHPUnit\Framework\TestCase;

class ImageLoaderTest extends TestCase
{
    public const string TEST_FILE = __DIR__ . '/Resources/valid.jpg';

    public function testLoad_OnlyGd()
    {
        $loader = new class() extends ImageLoader {
            protected function isImagickInstalled(): bool { return false; }
            protected function isGdInstalled(): bool { return true; }
        };

        try {
            $image = $loader->load(self::TEST_FILE);
            $this->assertInstanceOf(GdWrapper::class, $image);
        } catch (\Error $err) {
            $this->assertStringContainsString('Call to undefined function', $err->getMessage());
        }
    }

    public function testLoad_OnlyImagick()
    {
        $loader = new class() extends ImageLoader {
            protected function isImagickInstalled(): bool { return true; }
        };

        try {
            $image = $loader->load(self::TEST_FILE);
            $this->assertInstanceOf(Image::class, $image);
        } catch (\Error $err) {
            $this->assertStringContainsString('Class "Imagick" not found', $err->getMessage());
        }
    }
}
