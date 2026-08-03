<?php

namespace Beryllium\Icelus;

use GdImage;
use RuntimeException;

/**
 * Wraps the GD extension methods/classes to provide a consistent Imagick-like interface
 *
 * NOTE: This is not a comprehensive implementation, it only delivers Icelus-specific functionality.
 */
class GdWrapper implements Thumbable
{
    protected GdImage $image;
    protected GdImage $thumb;
    protected array $info;
    protected array $extraInfo = [];

    public int $width;
    public int $height;
    public int $thumbWidth;
    public int $thumbHeight;
    public string $mime;
    public string $extension;
    private ThumbnailMath $thumbDeets;

    public function __construct(protected string $filename)
    {
        $this->load($this->filename);
    }

    private function load(string $filename): void
    {
        $info = getimagesize($filename, $this->extraInfo);

        if (false === $info) {
            throw new RuntimeException("Encountered an invalid or unsupported image file: " . $filename);
        }

        $this->info = $info;
        $this->width = $info[0];
        $this->height = $info[1];
        $this->mime = image_type_to_mime_type($info[2]);
        $this->extension = image_type_to_extension($info[2], include_dot: false);

        $this->image = match ($this->mime) {
            'image/jpeg' => imagecreatefromjpeg($filename),
            'image/png' => imagecreatefrompng($filename),
            'image/webp' => imagecreatefromwebp($filename),
            'image/gif' => imagecreatefromgif($filename),
            default => imagecreatefromstring(file_get_contents($filename)),
        };
    }

    public function getFileExtension(): string
    {
        return strtolower($this->extension);
    }

    /**
     * Apply thumbnail calculations and create the thumbnail image in-memory
     */
    public function thumbnail(int $width, int $height, bool $crop = true): bool
    {
        $this->thumbDeets = new ThumbnailMath(
            srcWidth: $this->width,
            srcHeight: $this->height,
            destWidth: $width,
            destHeight: $height,
            crop: $crop,
        );

        $this->thumb = imagecreatetruecolor(
            width: $this->thumbDeets->destWidth,
            height: $this->thumbDeets->destHeight,
        );

        $this->thumbWidth = $this->thumbDeets->destWidth;
        $this->thumbHeight = $this->thumbDeets->destHeight;

        imagecopyresampled(
            dst_image: $this->thumb,
            src_image: $this->image,
            dst_x: $this->thumbDeets->destX,
            dst_y: $this->thumbDeets->destY,
            src_x: $this->thumbDeets->srcX,
            src_y: $this->thumbDeets->srcY,
            dst_width: $this->thumbDeets->newWidth,
            dst_height: $this->thumbDeets->newHeight,
            src_width: $this->width,
            src_height: $this->height
        );

        return true;
    }

    /**
     * Get thumbnail binary data
     */
    public function output(): string
    {
        ob_start();

        switch ($this->info[2]) {
            case IMAGETYPE_JPEG:
            case IMAGETYPE_JPEG2000:
                imagejpeg($this->thumb, null, 90);
                break;
            case IMAGETYPE_PNG:
                imagepng($this->thumb);
                break;
            case IMAGETYPE_WEBP:
                imagewebp($this->thumb);
                break;
            case IMAGETYPE_GIF:
                imagegif($this->thumb);
                break;
            default:
                throw new RuntimeException('Unhandled image format - could not create thumbnail');
                break;
        }

        $imageData = ob_get_contents();
        ob_end_clean();

        return $imageData;
    }

    /**
     * Write thumbnail binary data to disk
     */
    public function write(string $filename): bool
    {
        $result = file_put_contents($filename, $this->output());

        return false !== $result;
    }
}