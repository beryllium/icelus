Icelus
======

[![Build Status](https://travis-ci.org/beryllium/icelus.svg)](https://travis-ci.org/beryllium/icelus) [![SensioLabsInsight](https://insight.sensiolabs.com/projects/274bb02a-d709-484f-a0f0-5650f494a542/mini.png)](https://insight.sensiolabs.com/projects/274bb02a-d709-484f-a0f0-5650f494a542)

Icelus enables your Sculpin-based websites and blogs to generate space and bandwidth-saving thumbnails of images.

> _Icelus, otherwise known as "Scaled Sculpin", are a genus of small fish mainly found in the North Pacific._

Requirements
------------

Icelus requires:

* [PHP 8.5+](https://www.php.net/downloads.php)
* [PHP Composer](https://getcomposer.org/download/)
* Imagick or Gd extension (installable via apt-get, pecl, pie, homebrew, macports, or yum)

Installation
------------

You can run `composer require beryllium/icelus` to get things rolling.

Once the library is installed, you have to tell Sculpin how to load it. You can do this by creating or modifying the `app/SculpinKernel.php` file to resemble the following:

    <?php
    
    class SculpinKernel extends \Sculpin\Bundle\SculpinBundle\HttpKernel\AbstractKernel
    {
        protected function getAdditionalSculpinBundles(): array
        {
            return array(
                \Beryllium\Icelus\IcelusBundle::class,
            );
        }
    }

__Note:__ The class name should be either the class constant (shown above) or a string, not an object instantiation.

Configuration
-------------

Generally, no additional configuration is required. If you want to rename
the output subfolder for thumbnails (default is 'yourblog.com/_thumbs'),
add this to `app/config/sculpin_kernel.yml`:

``` yml
icelus:
    prefix: '/_thumbs'
```

* `icelus.prefix` : A subdirectory under the Sculpin output directory to store the thumbnails. Default is `'/_thumbs'`.

Usage
-----

Icelus exposes a `thumbnail` function in Twig, which you can use either on its own or by creating Twig macros to customize the output.

___thumbnail(image, width, height, crop)___

* __image__ (string): The relative path to the image in the `source/` folder.
* __width__ (int): Maximum width, in pixels
* __height__ (int): Maximum height, in pixels
* __crop__ (bool): False will fit the whole image inside the provided dimensions. True will crop the image from the center. Default: __FALSE__

__Note:__ The `crop` setting currently only works with the Imagick loader, which has built-in support. Gd allows cropping and many other advanced operations, but implementing them is more challenging.

Inline Example:

    <a href="image.jpg"><img src="{{ thumbnail('image.jpg', 100, 100) }}"></a>
    
Macro Example:

    index.html:
    
    {% import '_macros.html.twig' as m %}
    
    <h1>Gone Fishin'!</h1>
    {{ m.small_thumbnail('image.jpg', 'A picture from my fishing trip') }}
    
    
    _macros.html.twig: 
    
    {% macro small_thumbnail(image, caption) %}
      <a href="{{ image }}">
        <img src="{{ thumbnail(image, 100, 100) }}">
        <br>
        <em>{{ caption }}</em>
      </a>
    {% endmacro %}
    
A service called `icelus.service` is also added to the Sculpin dependency injection container, which you can use in your own Sculpin extensions. 

Future Plans
------------

I would like for Icelus to expose more features of the underlying image processing libraries, particularly with regard to watermarks and drawing text onto images.

Thanks
------

Special thanks to [Beau Simensen](https://github.com/simensen), for inviting me into the Sculpin organization, and to [Erika Heidi](https://github.com/erikaheidi) for the ease-of-use of the original Imanee library that worked for many years.

