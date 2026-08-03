<?php

namespace Beryllium\Icelus;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class ThumbnailMathTest extends TestCase
{
    #[DataProvider('sizeProvider_WithCrop')]
    #[DataProvider('sizeProvider_NoCrop')]
    public function testMath(
        $srcWidth,
        $srcHeight,
        $destWidth,
        $destHeight,
        $crop,
        $expectedDestX,
        $expectedDestY,
        $expectedDestRatio,
        $expectedSrcX,
        $expectedSrcY,
        $expectedSrcRatio,
        $expectedDestPreCropWidth,
        $expectedDestPreCropHeight,
        $expectedDestWidth,
        $expectedDestHeight,
    ): void {
        $thumb = new ThumbnailMath($srcWidth, $srcHeight, $destWidth, $destHeight, $crop);

        self::assertSame($expectedDestRatio, $thumb->destRatio);
        self::assertSame($expectedSrcRatio, $thumb->srcRatio);

        if ($crop) {
            self::assertSame($expectedDestPreCropWidth, $thumb->preCropWidth);
            self::assertSame($expectedDestPreCropHeight, $thumb->preCropHeight);
        } else {
            self::assertSame($expectedDestWidth, $thumb->destWidth);
            self::assertSame($expectedDestHeight, $thumb->destHeight);
        }

        self::assertSame($expectedDestWidth, $thumb->newWidth);
        self::assertSame($expectedDestHeight, $thumb->newHeight);

        self::assertSame($expectedDestX, $thumb->destX);
        self::assertSame($expectedDestY, $thumb->destY);
        self::assertSame($expectedSrcX, $thumb->srcX);
        self::assertSame($expectedSrcY, $thumb->srcY);
    }

    public static function sizeProvider_WithCrop(): \Generator
    {
        yield '1080p-to-240p' => [
            'srcWidth' => 1920,
            'srcHeight' => 1080,
            'destWidth' => 320,
            'destHeight' => 240,
            'crop' => true,
            'expectedDestX' => 0,
            'expectedDestY' => 0,
            'expectedDestRatio' => 4/3,
            'expectedSrcX' => 268,
            'expectedSrcY' => 0,
            'expectedSrcRatio' => 16/9,
            'expectedDestPreCropWidth' => 427,
            'expectedDestPreCropHeight' => 240,
            'expectedDestWidth' => 320,
            'expectedDestHeight' => 240,
        ];

        yield '1080p-to-square' => [
            'srcWidth' => 1920,
            'srcHeight' => 1080,
            'destWidth' => 350,
            'destHeight' => 350,
            'crop' => true,
            'expectedDestX' => 0,
            'expectedDestY' => 0,
            'expectedDestRatio' => 1.0,
            'expectedSrcX' => 408,
            'expectedSrcY' => 0,
            'expectedSrcRatio' => 16/9,
            'expectedDestPreCropWidth' => 622,
            'expectedDestPreCropHeight' => 350,
            'expectedDestWidth' => 350,
            'expectedDestHeight' => 350,
        ];

        yield 'landscape-to-square' => [
            'srcWidth' => 2232,
            'srcHeight' => 1628,
            'destWidth' => 350,
            'destHeight' => 350,
            'crop' => true,
            'expectedDestX' => 0,
            'expectedDestY' => 0,
            'expectedDestRatio' => 1.0,
            'expectedSrcX' => 325,
            'expectedSrcY' => 0,
            'expectedSrcRatio' => 558/407,
            'expectedDestPreCropWidth' => 480,
            'expectedDestPreCropHeight' => 350,
            'expectedDestWidth' => 350,
            'expectedDestHeight' => 350,
        ];

        yield '1080p-to-portrait' => [
            'srcWidth' => 1920,
            'srcHeight' => 1080,
            'destWidth' => 207,
            'destHeight' => 368,
            'crop' => true,
            'expectedDestX' => 0,
            'expectedDestY' => 0,
            'expectedDestRatio' => 9/16,
            'expectedSrcX' => 671,
            'expectedSrcY' => 0,
            'expectedSrcRatio' => 16/9,
            'expectedDestPreCropWidth' => 654,
            'expectedDestPreCropHeight' => 368,
            'expectedDestWidth' => 207,
            'expectedDestHeight' => 368,
        ];

        yield 'square-to-landscape' => [
            'srcWidth' => 4000,
            'srcHeight' => 4000,
            'destWidth' => 320,
            'destHeight' => 240,
            'crop' => true,
            'expectedDestX' => 0,
            'expectedDestY' => 0,
            'expectedDestRatio' => 4/3,
            'expectedSrcX' => 0,
            'expectedSrcY' => 520,
            'expectedSrcRatio' => 1.0,
            'expectedDestPreCropWidth' => 320,
            'expectedDestPreCropHeight' => 320,
            'expectedDestWidth' => 320,
            'expectedDestHeight' => 240,
        ];

        yield 'square-to-square' => [
            'srcWidth' => 4000,
            'srcHeight' => 4000,
            'destWidth' => 350,
            'destHeight' => 350,
            'crop' => true,
            'expectedDestX' => 0,
            'expectedDestY' => 0,
            'expectedDestRatio' => 1.0,
            'expectedSrcX' => 0,
            'expectedSrcY' => 0,
            'expectedSrcRatio' => 1.0,
            'expectedDestPreCropWidth' => 350,
            'expectedDestPreCropHeight' => 350,
            'expectedDestWidth' => 350,
            'expectedDestHeight' => 350,
        ];

        yield 'portrait-to-square' => [
            'srcWidth' => 4000,
            'srcHeight' => 6000,
            'destWidth' => 350,
            'destHeight' => 350,
            'crop' => true,
            'expectedDestX' => 0,
            'expectedDestY' => 0,
            'expectedDestRatio' => 1.0,
            'expectedSrcX' => 0,
            'expectedSrcY' => 963,
            'expectedSrcRatio' => 2/3,
            'expectedDestPreCropWidth' => 350,
            'expectedDestPreCropHeight' => 525,
            'expectedDestWidth' => 350,
            'expectedDestHeight' => 350,
        ];

        yield 'portrait-to-landscape' => [
            'srcWidth' => 4000,
            'srcHeight' => 6000,
            'destWidth' => 320,
            'destHeight' => 240,
            'crop' => true,
            'expectedDestX' => 0,
            'expectedDestY' => 0,
            'expectedDestRatio' => 4/3,
            'expectedSrcX' => 0,
            'expectedSrcY' => 1560,
            'expectedSrcRatio' => 2/3,
            'expectedDestPreCropWidth' => 320,
            'expectedDestPreCropHeight' => 480,
            'expectedDestWidth' => 320,
            'expectedDestHeight' => 240,
        ];

        yield 'square-to-portrait' => [
            'srcWidth' => 4000,
            'srcHeight' => 4000,
            'destWidth' => 240,
            'destHeight' => 320,
            'crop' => true,
            'expectedDestX' => 0,
            'expectedDestY' => 0,
            'expectedDestRatio' => 3/4,
            'expectedSrcX' => 520,
            'expectedSrcY' => 0,
            'expectedSrcRatio' => 1.0,
            'expectedDestPreCropWidth' => 320,
            'expectedDestPreCropHeight' => 320,
            'expectedDestWidth' => 240,
            'expectedDestHeight' => 320,
        ];

        yield 'portrait-to-portrait' => [
            'srcWidth' => 4000,
            'srcHeight' => 6000,
            'destWidth' => 400,
            'destHeight' => 600,
            'crop' => true,
            'expectedDestX' => 0,
            'expectedDestY' => 0,
            'expectedDestRatio' => 2/3,
            'expectedSrcX' => 0,
            'expectedSrcY' => 0,
            'expectedSrcRatio' => 2/3,
            'expectedDestPreCropWidth' => 400,
            'expectedDestPreCropHeight' => 600,
            'expectedDestWidth' => 400,
            'expectedDestHeight' => 600,
        ];

        yield 'portrait-to-portrait-narrower' => [
            'srcWidth' => 4000,
            'srcHeight' => 6000,
            'destWidth' => 240,
            'destHeight' => 320,
            'crop' => true,
            'expectedDestX' => 0,
            'expectedDestY' => 0,
            'expectedDestRatio' => 3/4,
            'expectedSrcX' => 0, // one of these must be wrong
            'expectedSrcY' => 340, // one of these must be wrong
            'expectedSrcRatio' => 2/3,
            'expectedDestPreCropWidth' => 240, // this is wrong
            'expectedDestPreCropHeight' => 360,
            'expectedDestWidth' => 240,
            'expectedDestHeight' => 320,
        ];
    }

    public static function sizeProvider_NoCrop(): \Generator
    {
        $init = [
            'crop' => false,
            'expectedDestX' => 0,
            'expectedDestY' => 0,
            'expectedSrcX' => 0,
            'expectedSrcY' => 0,
            'expectedDestPreCropWidth' => null,
            'expectedDestPreCropHeight' => null,
        ];

        yield 'landscape-to-square-no-crop' => [
                'srcWidth' => 2232,
                'srcHeight' => 1628,
                'destWidth' => 350,
                'destHeight' => 350,
                'expectedDestRatio' => 1.0,
                'expectedSrcRatio' => 558 / 407,
                'expectedDestWidth' => 350,
                'expectedDestHeight' => 255,
            ] + $init;

        yield 'square-to-square-no-crop' => [
                'srcWidth' => 4000,
                'srcHeight' => 4000,
                'destWidth' => 350,
                'destHeight' => 350,
                'expectedDestRatio' => 1.0,
                'expectedSrcRatio' => 1.0,
                'expectedDestWidth' => 350,
                'expectedDestHeight' => 350,
            ] + $init;

        yield 'portrait-to-square-no-crop' => [
                'srcWidth' => 4000,
                'srcHeight' => 6000,
                'destWidth' => 350,
                'destHeight' => 350,
                'expectedDestRatio' => 1.0,
                'expectedSrcRatio' => 2 / 3,
                'expectedDestWidth' => 233,
                'expectedDestHeight' => 350,
            ] + $init;

        yield 'portrait-to-landscape-no-crop' => [
                'srcWidth' => 4000,
                'srcHeight' => 6000,
                'destWidth' => 320,
                'destHeight' => 240,
                'expectedDestRatio' => 4 / 3,
                'expectedSrcRatio' => 2 / 3,
                'expectedDestWidth' => 213,
                'expectedDestHeight' => 240,
            ] + $init;

        yield 'square-to-portrait-no-crop' => [
                'srcWidth' => 4000,
                'srcHeight' => 4000,
                'destWidth' => 240,
                'destHeight' => 320,
                'expectedDestRatio' => 3 / 4,
                'expectedSrcRatio' => 1.0,
                'expectedDestWidth' => 240,
                'expectedDestHeight' => 320,
            ] + $init;

        yield 'portrait-to-portrait-no-crop' => [
                'srcWidth' => 4000,
                'srcHeight' => 6000,
                'destWidth' => 400,
                'destHeight' => 600,
                'expectedDestRatio' => 2 / 3,
                'expectedSrcRatio' => 2 / 3,
                'expectedDestWidth' => 400,
                'expectedDestHeight' => 600,
            ] + $init;

        yield 'portrait-to-portrait-narrower-no-crop' => [
                'srcWidth' => 4000,
                'srcHeight' => 6000,
                'destWidth' => 240,
                'destHeight' => 320,
                'expectedDestRatio' => 3 / 4,
                'expectedSrcRatio' => 2 / 3,
                'expectedDestWidth' => 160,
                'expectedDestHeight' => 320,
            ] + $init;
    }
}
