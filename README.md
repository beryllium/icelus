Icelus
======

[![Build Status](https://travis-ci.org/beryllium/icelus.svg)](https://travis-ci.org/beryllium/icelus) [![SensioLabsInsight](https://insight.sensiolabs.com/projects/274bb02a-d709-484f-a0f0-5650f494a542/mini.png)](https://insight.sensiolabs.com/projects/274bb02a-d709-484f-a0f0-5650f494a542)

Icelus is a quick and easy thumbnail generator for your Sculpin-based websites and blogs.

> _Icelus, otherwise known as "Scaled Sculpin", are a genus of small fish mainly found in the North Pacific._

Requirements
------------

Icelus requires:

* PHP 5.4+
* Imagick extension (installable via apt-get, pecl, or yum)
* Imanee library ([imanee.io](http://imanee.io) - fetched automatically by Composer)

Installation
------------

If you are using the Phar-based Sculpin utility, you can create or modify a sculpin.json file in your project root and add `"beryllium/icelus"` to the `"require"` block. Then, run `sculpin install` or `sculpin update` to fetch the required dependencies.

    {
      "require": {
         "beryllium/icelus": "*"
      }
    }
    
Alternatively, if you are using a Composer-based sculpin installation, you should simply be able to run `composer require beryllium/icelus` to get things rolling.

Once the library is installed, you have to tell Sculpin how to load it. You can do this by creating or modifying a `app/SculpinKernel.php` file to resemble the following:

    <?php
    
    class SculpinKernel extends \Sculpin\Bundle\SculpinBundle\HttpKernel\AbstractKernel
    {
        protected function getAdditionalSculpinBundles()
        {
            return array(
                'Beryllium\Icelus\IcelusBundle',
            );
        }
    }

__Note:__ The class name should be a string, not an object instantiation. (This differs from the way Symfony 2 configures bundles.)

Configuration
-------------

Add to `sculpin_kernel.yml`

``` yml
icelus:
    prefix: '/_thumbs'
```

* `icelus.prefix` : A part of the path used as the prefix of the output path. default is `'/_thumbs'`.

Usage
-----

Icelus exposes a `thumbnail` function in Twig, which you can use either on its own or by creating Twig macros to customize the output.

___thumbnail(image, width, height, crop)___

* __image__ (string): The relative path to the image in the `source/` folder.
* __width__ (int): Maximum width, in pixels
* __height__ (int): Maximum height, in pixels
* __crop__ (bool): False will fit the whole image inside the provided dimensions. True will crop the image from the center. Default: __FALSE__

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

For raw access to the underlying Imanee library, the service is named `icelus.imanee`. If you need to go deeper, you can then retrieve an Imagick instance using `$imanee->getIMResource()`.

Technically speaking, this extension could also be used as a Symfony 2 bundle. This has not been tested, but experimentation is welcome.

Future Plans
------------

I would like for Icelus to expose more features of the underlying Imanee library, particularly with regard to watermarks and drawing text onto images. Imanee's support for animated gifs could possibly also be advantageous in some way.

I would also like for Icelus to be compatible with a wide variety of PHP frameworks and workflows. I've concentrated on having it as a Twig extension, but it could also work with other template systems and even Markdown-style parsers.

Thanks
------

Special thanks to [Beau Simensen](https://github.com/simensen), for inviting me into the Sculpin organization, and to [Erika Heidi](https://github.com/erikaheidi) for the ease-of-use of the Imanee library.

