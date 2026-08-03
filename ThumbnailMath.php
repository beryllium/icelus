<?php

namespace Beryllium\Icelus;

/**
 * Provides thumbnail calculations so that GdWrapper can emulate ImageMagick::cropThumbnailImage
 */
class ThumbnailMath
{
    public float $srcRatio;
    public float $destRatio;

    public int $preCropWidth;
    public int $preCropHeight;

    public int $newWidth;
    public int $newHeight;

    public int $destX;
    public int $destY;

    public int $srcX;
    public int $srcY;

    public function __construct(
        public readonly int $srcWidth,
        public readonly int $srcHeight,
        public int $destWidth,
        public int $destHeight,
        public readonly bool $crop = true,
    ) {
        $this->calculateRatios();

        if ($crop) {
            $this->calculatePreCropXY();
            $this->calculateCropOffset();
        } else {
            $this->calculateResizeXY();
        }
    }

    /**
     * Calculates aspect ratios of the Src and Dest dimensions
     */
    private function calculateRatios(): void
    {
        $this->srcRatio = $this->srcWidth / $this->srcHeight;
        $this->destRatio = $this->destWidth / $this->destHeight;
    }

    /**
     * For non-cropped images, calculates the newHeight/newWidth and re-calculates destHeight/destWidth
     */
    private function calculateResizeXY(): void
    {
        $isDestWider = $this->srcRatio < $this->destRatio;
        $isDestNarrower = $this->srcRatio > $this->destRatio;

        $this->destX = 0;
        $this->destY = 0;
        $this->srcX = 0;
        $this->srcY = 0;

        if ($isDestWider) {
            $this->newHeight = $this->destHeight;
            $this->newWidth = round($this->destWidth * $this->srcRatio);
            $this->destWidth = $this->newWidth;
        } else if ($isDestNarrower) {
            $this->newWidth = $this->destWidth;
            $this->newHeight = round($this->destHeight / $this->srcRatio);
            $this->destHeight = $this->newHeight;
        } else {
            $this->newWidth = $this->destWidth;
            $this->newHeight = $this->destHeight;
        }
    }

    /**
     * Scales cropped images prior to cropping, in order to maximize available pixels
     */
    private function calculatePreCropXY(): void
    {
        $isDestWider = $this->srcRatio < $this->destRatio;

        if ($isDestWider) {
            $widthScale = $this->srcWidth / $this->destWidth;
            $this->preCropWidth = round($this->srcWidth / $widthScale);
            $this->preCropHeight = round($this->srcHeight / $widthScale);
        } else {
            $heightScale = $this->srcHeight / $this->destHeight;
            $this->preCropWidth = round($this->srcWidth / $heightScale);
            $this->preCropHeight = $this->destHeight;
        }
    }

    /**
     * Applies crop logic to scaled image, so that imagecopyresampled can have the right params
     */
    private function calculateCropOffset(): void
    {
        $this->destX = 0;
        $this->destY = 0;

        $this->srcX = 0;
        $this->srcY = 0;

        if ($this->preCropWidth > $this->destWidth) {
            $cropOffset = ($this->preCropWidth - $this->destWidth) / 2;
            $heightScale = round($this->srcHeight / $this->destHeight);
            $this->srcX = round($cropOffset * $heightScale);
        }

        if ($this->preCropHeight > $this->destHeight) {
            $cropOffset = ($this->preCropHeight - $this->destHeight) / 2;
            $widthScale = round($this->srcWidth / $this->destWidth);
            $this->srcY = round($cropOffset * $widthScale);
        }

        $this->newWidth = $this->destWidth;
        $this->newHeight = $this->destHeight;
    }
}