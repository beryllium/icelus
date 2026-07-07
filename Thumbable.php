<?php

namespace Beryllium\Icelus;

interface Thumbable
{
    public function getFileExtension(): string;
    public function output(): string;
    public function thumbnail(int $width, int $height, bool $crop = true): bool;
    public function write(string $filename): bool;
}