Creating Animated Gifs
======================

Imanee has various convenient methods to generate animated gifs. Notice that the animated gif features are currently only supported when you are using the **ImagickResource** - you need the Imagick extension for that.

Creating an animated gif from various Imanee objects
----------------------------------------------------

The basic method for animating gifs is the ``animate`` method. You should add frames to the Imanee object using the ``addFrame`` method before using ``animate``.
The method ``addFrame`` accepts either Imanee objects or strings representing a path to an image.

``animate($delay = 20)``

This will generate an animated gif with a text changing colors:

.. code-block:: php

    header("Content-type: image/gif");

    $text = "Imanee!";
    $font = __DIR__ . '/resources/almonte_wood.ttf';
    $colors = ['green', 'red', 'yellow', 'blue'];

    $base = new Imanee();
    $drawer = new Drawer();

    $drawer->setFont($font)
        ->setFontSize(80);

    foreach ($colors as $color) {
        $drawer->setFontColor($color);
        $frame = Imanee::textGen($text, $drawer);
        $base->addFrame($frame);
    }

    echo $base->animate();

.. image:: img/animated_text.gif

Creating an animated gif from images in a directory
---------------------------------------------------

The ``globAnimate`` static method can be used to easily generate animated gifs from a directory pattern.

``string Imanee::globAnimate($pattern, $delay = 20)``

.. code-block:: php

    header("Content-type: image/gif");

    $gif = Imanee::globAnimate(__DIR__ . '/../resources/*.png');
    echo $gif->output();


Creating an animated gif from images in an array
------------------------------------------------

The ``arrayAnimate`` static method can be used to generate animated gifs from an array with paths to image files.

``string Imanee::arrayAnimate(array $images, $delay = 20)``

.. code-block:: php

    $frames[] = __DIR__ . '/../resources/cat01.png';
    $frames[] = __DIR__ . '/../resources/cat02.png';
    $frames[] = __DIR__ . '/../resources/cat03.png';
    $frames[] = __DIR__ . '/../resources/cat04.png';

    header("Content-type: image/gif");

    $gif = Imanee::arrayAnimate($frames, 30);
    echo $gif->output();
