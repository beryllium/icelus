<?php

namespace Beryllium\Icelus;

class GdWrapper implements Thumbable
{
    protected \GdImage $image;
    protected \GdImage $thumb;
    protected array $info;
    protected array $extraInfo = [];

    public int $width;
    public int $height;
    public int $thumbWidth;
    public int $thumbHeight;
    public string $mime;
    public string $extension;

    public function __construct(protected string $filename)
    {
        $this->load($this->filename);
    }

    private function load(string $filename): void
    {
        $info = getimagesize($filename, $this->extraInfo);

        if (false === $info) {
            throw new \RuntimeException("Encountered an invalid or unsupported image file: " . $filename);
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

    public function thumbnail(int $width, int $height, bool $crop = true): bool
    {
        $this->thumbWidth = $width;
        $this->thumbHeight = $height;

        $srcX = $srcY = $dstX = $dstY = 0;

        $ratio  = $this->width > 0 && $this->height > 0 ? $this->width / $this->height : 1;
        $ratio  = $ratio > 0.00 ? $ratio : 1.0;

        $thumbRatio = $this->thumbWidth > 0 && $this->thumbHeight > 0 ? $this->thumbWidth / $this->thumbHeight : 1;
        $thumbRatio  = $thumbRatio > 0.00 ? $thumbRatio : 1.0;

        if (abs($ratio - $thumbRatio) > 1) {
            // Calculate cropping window
            if ($crop) {

            }
            throw new \RuntimeException(<<<EOT
                Thumbnail ratio does not match image ratio:
                
                Image Height: {$this->height}
                Image Width: {$this->width}
                Image Aspect Ratio: {$ratio }
                
                Thumb Height: {$this->thumbHeight}
                Thumb Width: {$this->thumbWidth}
                Thumb Aspect Ratio: {$thumbRatio}
                
                src X: $srcX
                src Y: $srcY
                EOT
            );
        }

        $newWidth  = min($this->width, $this->thumbWidth);
        $newHeight = (int)ceil($newWidth / $ratio);

        if ($newHeight > $this->thumbHeight) {
            $newHeight = $this->thumbHeight;
            $newWidth  = (int)ceil($this->thumbHeight * $ratio);
        }
        // @todo update this logic to factor in the $crop setting
        // if ($this->height > $this->thumbHeight) {
        //     $newWidth = ($this->thumbHeight / $this->height) * $this->width;
        //     $newHeight = $height;
        // }
        //
        // if ($this->width > $this->thumbWidth) {
        //     $newHeight = ($this->thumbWidth / $this->width) * $this->height;
        //     $newWidth = $this->thumbWidth;
        // }

        $this->thumb = imagecreatetruecolor(
            width: $newWidth,
            height: $newHeight
        );

        imagecopyresampled(
            dst_image: $this->thumb,
            src_image: $this->image,
            dst_x: $dstX,
            dst_y: $dstY,
            src_x: $srcX,
            src_y: $srcY,
            dst_width: $newWidth,
            dst_height: $newHeight,
            src_width: $this->width,
            src_height: $this->height
        );

        return true;
    }

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
                throw new \RuntimeException('Unhandled image format - could not create thumbnail');
                break;
        }

        $imageData = ob_get_contents();
        ob_end_clean();

        return $imageData;
    }

    public function write(string $filename): bool
    {
        $result = file_put_contents($filename, $this->output());

        return false !== $result;
    }
}