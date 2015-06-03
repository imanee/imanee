Getting Started
===============

Imanee is a simple wrapper to facilitate the use of ImageMagick and GD in PHP. Imanee provides several convenient methods for dealing with images, generating
text and animated gifs, applying filters and funny effects to images, amongst other things.

Requirements
------------
Imanee requires PHP >= 5.4 and a supported image extension to be installed and loaded in the PHP server. We currently support **Imagick** and **GD**. Imanee
will always try to use Imagick by default, but if it cannot find the *Imagick* extension loaded, it will try to use GD instead. You can also override this behavior
by providing a *ImageResource* object when instantiating Imanee.

Installation
------------
Installation can be made easily through composer:

.. code-block:: bash

    $ composer require imanee/imanee


Check the latest stable version on Packagist: `imanee/imanee <https://packagist.org/packages/imanee/imanee>`_

Getting Started
---------------

A few simple examples to get you started

1. Thumbnail output:

.. code-block:: php

    header("Content-type: image/jpg");

    $imanee = new Imanee('path/to/my/image.jpg');
    echo $imanee->thumbnail(200, 200)->output();

2. Resizing an image:

.. code-block:: php

    header("Content-type: image/jpg");

    $imanee = new Imanee('path/to/my/image.jpg');
    echo $imanee->resize(200, 200)->output();


3. Writing centralized text on top of an image:

.. code-block:: php

    header("Content-type: image/jpg");

    $imanee = new Imanee('path/to/my/image.jpg');
    echo $imanee->placeText('imanee test', Imanee::IM_POS_MID_CENTER)->output();

4. Adding a translucid watermark:

.. code-block:: php

    header("Content-type: image/jpg");

    $imanee = new Imanee('path/to/my/image.jpg');
    echo $imanee->watermark('path/to/my/image.png', Imanee::IM_POS_BOTTOM_RIGHT, 50)->output();

5. Forcing use of GD:
Sometimes it might be useful to force usage of a specific Image Resource type.
You can just pass an object implementing the ImageResource interface as a second argument to the Imanee constructor.

.. code-block:: php

    header("Content-type: image/jpg");

    $imanee = new Imanee('path/to/my/image.jpg', new GDResource());
    echo $imanee->thumbnail(200, 200)->output();