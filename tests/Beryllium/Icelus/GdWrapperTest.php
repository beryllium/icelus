<?php

namespace Beryllium\Icelus;

use PHPUnit\Framework\TestCase;

class GdWrapperTest extends TestCase
{
    public function testThumbnail_Portrait_NoCrop()
    {
        $image = new GdWrapper(__DIR__ . '/../../Resources/valid.jpg');

        self::assertSame('jpeg', $image->getFileExtension());

        $image->thumbnail(100, 100, false);

        self::assertSame(100, $image->thumbHeight);
        self::assertSame(67, $image->thumbWidth);
    }

    public function testThumbnail_Landscape_NoCrop()
    {
        $image = new GdWrapper(__DIR__ . '/../../Resources/valid-landscape.jpg');

        self::assertSame('jpeg', $image->getFileExtension());

        $image->thumbnail(100, 100, false);

        self::assertSame(70, $image->thumbHeight);
        self::assertSame(100, $image->thumbWidth);
    }

    public function testThumbnail_Pano_NoCrop()
    {
        $image = new GdWrapper(__DIR__ . '/../../Resources/valid-pano.jpg');

        self::assertSame('jpeg', $image->getFileExtension());

        $image->thumbnail(100, 100, false);

        self::assertSame(44, $image->thumbHeight);
        self::assertSame(100, $image->thumbWidth);
    }

    public function testThumbnail_Portrait()
    {
        $image = new GdWrapper(__DIR__ . '/../../Resources/valid.jpg');

        self::assertSame('jpeg', $image->getFileExtension());

        $image->thumbnail(100, 100);

        self::assertSame(100, $image->thumbHeight);
        self::assertSame(100, $image->thumbWidth);
    }

    public function testThumbnail_Landscape()
    {
        $image = new GdWrapper(__DIR__ . '/../../Resources/valid-landscape.jpg');

        self::assertSame('jpeg', $image->getFileExtension());

        $image->thumbnail(100, 100);

        self::assertSame(100, $image->thumbHeight);
        self::assertSame(100, $image->thumbWidth);
    }

    public function testThumbnail_Pano()
    {
        $image = new GdWrapper(__DIR__ . '/../../Resources/valid-pano.jpg');

        self::assertSame('jpeg', $image->getFileExtension());

        $image->thumbnail(100, 100);

        self::assertSame(100, $image->thumbHeight);
        self::assertSame(100, $image->thumbWidth);
    }
}
