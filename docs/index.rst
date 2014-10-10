.. Imanee documentation master file, created by
   sphinx-quickstart on Fri Oct 10 12:25:22 2014.
   You can adapt this file completely to your liking, but it should at least
   contain the root `toctree` directive.

Imanee documentation!
=====================
Imanee is a simple wrapper library for Imagemagick. It provides an easy flow for editing images and performing common tasks such as thumbnails, watermarks and text writing.
This is an experimental project under development.

Contents:

.. toctree::
   :maxdepth: 2

Requirements
------------
Imanee requires the *imagick* PHP extension, and PHP >= 5.4 .

Installation
------------
Installation can be made through composer::

    "require": {
        "imanee/imanee": "dev-master@dev"
    }


Usage examples
--------------

1. Thumbnail output::

        header("Content-type: image/jpg");

        $imanee = new \Imanee('path/to/my/image.jpg');
        echo $imanee->thumbnail(200, 200)->output();

2. Image composition::

    header("Content-type: image/jpg");

    $imanee = new \Imanee('path/to/my/image.jpg');

    /* places 4 different png images on the 4 corners of the original image */

    echo $imanee->placeImage('img1.png', \Imanee\Imanee::IM_POS_TOP_LEFT)
        ->placeImage('img2.png', \Imanee\Imanee::IM_POS_TOP_RIGHT)
        ->placeImage('img3.png', \Imanee\Imanee::IM_POS_BOTTOM_LEFT)
        ->placeImage('img4.png', \Imanee\Imanee::IM_POS_BOTTOM_RIGHT)
        ->output()
    ;

For more (and complete) examples see: `https://github.com/imanee/demos <https://github.com/imanee/demos>`_
