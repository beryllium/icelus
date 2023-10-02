<?php

namespace Beryllium\Icelus;

use Twig\Extension\AbstractExtension;
use Twig\Extension\ExtensionInterface;
use Twig\TwigFunction;

/**
 * Exposes a "thumbnail" function to Twig templates
 */
class TwigImageExtension extends AbstractExtension implements ExtensionInterface
{
    public ImageService $service;

    public function __construct(ImageService $service) {
        $this->service = $service;
    }

    public function getName(): string
    {
        return 'image_extension';
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('thumbnail', [$this->service, 'thumbnail'])
        ];
    }
}
