<?php

namespace Beryllium\Icelus;

use Imanee\Imanee;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Service for Image Manipulation.
 */
class ImageService
{
    public Imanee $imanee;
    public Filesystem $filesystem;

    public string $source_dir;
    public string $output_dir;
    public ?string $prefix;
    public array $completed = [];

    const DEFAULT_PREFIX = '/_thumbs';

    /**
     * Constructor
     *
     * @param Imanee        $imanee         Performs the required image manipulations
     * @param string        $source_dir     Where to find the images
     * @param mixed         $output_writer  Where to save the images
     * @param string|null   $prefix         subdirectory under output_dir to save the images (Default: '/_thumbs')
     * @param Filesystem    $filesystem     Filesystem class for doing filesystem things
     */
    public function __construct(Imanee $imanee, string $source_dir, $output_writer, ?string $prefix, Filesystem $filesystem)
    {
        $this->imanee     = $imanee;
        $this->source_dir = rtrim($source_dir, '/');
        $this->output_dir = rtrim($output_writer->getOutputDir(), '/');
        $this->prefix     = $prefix ? rtrim($prefix, '/') : static::DEFAULT_PREFIX;
        $this->filesystem = $filesystem;
    }

    /**
     * Prepare the output directory.
     *
     * This makes sure we have somewhere to put the thumbnails once we've generated them.
     */
    protected function prepOutputDir(): void
    {
        if (!is_dir($this->output_dir . $this->prefix)) {
            $this->filesystem->mkdir($this->output_dir . $this->prefix);
        }
    }

    /**
     * Generate a thumbnail
     *
     * @param string    $image      Path to image file (relative to source_dir)
     * @param int       $width      Width, in pixels (default: 150)
     * @param int       $height     Height, in pixels (default: 150)
     * @param bool      $crop       When set to true, the thumbnail will be cropped
     *                              from the center to match the given size
     *
     * @return string               Location of the thumbnail, for use in <img> tags
     */
    public function thumbnail(string $image, int $width = 150, int $height = 150, bool $crop = false): string
    {
        // no sense duplicating work - only process image if thumbnail doesn't already exist
        if (!isset($this->completed[$image][$width][$height][$crop]['filename'])) {
            $this->prepOutputDir();
            $this->imanee->load($this->source_dir . '/' . $image)->thumbnail($width, $height, $crop);
            $thumb_name = vsprintf(
                '%s-%sx%s%s.%s',
                [
                    md5($image),
                    $width,
                    $height,
                    ($crop ? '-cropped' : ''),
                    strtolower($this->imanee->getFormat())
                ]
            );

            // write the thumbnail to disk
            file_put_contents(
                $this->output_dir . $this->prefix . '/' . $thumb_name,
                $this->imanee->output()
            );
            $this->completed[$image][$width][$height][$crop]['filename'] = $thumb_name;
        }

        return $this->prefix . '/' . $this->completed[$image][$width][$height][$crop]['filename'];
    }
}
