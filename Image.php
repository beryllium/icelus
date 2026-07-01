<?php

namespace Beryllium\Icelus;

class Image
{
    protected readonly \Imagick $image;

    /**
     * @throws \ImagickException
     */
    public function __construct(protected ?string $filename = null)
    {
        $this->load($this->filename);
    }

    /**
     * @throws \ImagickException
     */
    protected function load(string $filename): self
    {
        $this->filename = $filename;
        $this->image = new \Imagick($filename);

        return $this;
    }

    /**
     * @throws \ImagickException
     */
    public function getFileExtension(): string
    {
        return strtolower($this->image->getImageFormat());
    }

    /**
     * @throws \ImagickException
     */
    public function thumbnail(int $width, int $height, bool $crop = true): bool
    {
        if ($crop) {
            return $this->image->cropThumbnailImage($width, $height);
        }

        return $this->image->thumbnailImage($width, $height, bestfit: true);
    }

    /**
     * @throws \ImagickException
     */
    public function output(): string
    {
        return $this->image->getImageBlob();
    }

    public function write(string $filename): bool
    {
        try {
            $result = file_put_contents($filename, $this->output());
            return false !== $result;
        } catch (\Exception $e) {
            return false;
        }
    }
}