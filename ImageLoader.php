<?php

namespace Beryllium\Icelus;

class ImageLoader
{
    public function load(string $filename): ?Thumbable
    {
        return match(true) {
            $this->isImagickInstalled() => new Image($filename),
            $this->isGdInstalled() => new GdWrapper($filename),
            default => throw new \Exception('Icelus requires either php-imagick or php-gd, neither was found'),
        };
    }

    protected function isImagickInstalled(): bool
    {
        return extension_loaded('imagick');
    }

    protected function isGdInstalled(): bool
    {
        return extension_loaded('gd');
    }
}