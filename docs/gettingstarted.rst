Getting Started
===============

Imanee is a simple wrapper to facilitate the use of ImageMagick (Imagick extension) in PHP. Imanee provides several convenient methods for dealing with images, generating
text and animated gifs, applying filters and funny effects to images, amongst other things.

Requirements
------------
Imanee requires the *imagick* PHP extension, and PHP >= 5.4 .

Installation
------------
Installation can be made easily through composer::

    $ composer require imanee/imanee "~0.2"


Check the latest stable version on Packagist: `imanee/imanee <https://packagist.org/packages/imanee/imanee>`_

Getting Started
---------------

A few simple examples to get you started

1. Thumbnail output::

        header("Content-type: image/jpg");

        $imanee = new Imanee('path/to/my/image.jpg');
        echo $imanee->thumbnail(200, 200)->output();

2. Resizing an image::

        header("Content-type: image/jpg");

        $imanee = new Imanee('path/to/my/image.jpg');
        echo $imanee->resize(200, 200)->output();


3. Writing centralized text on top of an image::

        header("Content-type: image/jpg");

        $imanee = new Imanee('path/to/my/image.jpg');
        echo $imanee->placeText('imanee test', Imanee::IM_POS_MID_CENTER)->output();

4. Adding a translucid watermark::

        header("Content-type: image/jpg");

        $imanee = new Imanee('path/to/my/image.jpg');
        echo $imanee->watermark('path/to/my/image.png', Imanee::IM_POS_BOTTOM_RIGHT, 50)->output();

