Creating Animated Gifs
======================

Imanee has various convenient methods to generate animated gifs.

Creating an animated gif from images in a directory
---------------------------------------------------

The ``globAnimate`` static method can be used to easily generate animated gifs from a directory pattern.

``string Imanee::globAnimate($pattern, $delay = 20)``

::

        header("Content-type: image/gif");

        echo Imanee::globAnimate(__DIR__ . '/../resources/*.png');

Creating an animated gif from images in an array
------------------------------------------------

The ``arrayAnimate`` static method can be used to generate animated gifs from an array with paths to image files.

``string Imanee::arrayAnimate(array $images, $delay = 20)``

::

        $frames[] = __DIR__ . '/../resources/cat01.png';
        $frames[] = __DIR__ . '/../resources/cat02.png';
        $frames[] = __DIR__ . '/../resources/cat03.png';
        $frames[] = __DIR__ . '/../resources/cat04.png';

        header("Content-type: image/gif");

        echo Imanee::arrayAnimate($frames, 30);

Creating an animated gif from various Imanee objects
----------------------------------------------------

You can also use the OO approach and add each frame to an Imanee object before animating it. This is useful to create
animated gifs using images with filters applied, or text-only gifs for instance.

``string Imanee::animate($delay = 20)``

This will generate an animated gif with a text changing colors::

        header("Content-type: image/gif");

        $text = "Imanee!";
        $font = __DIR__ . '/../resources/fonts/moderna.ttf';
        $colors = ['green', 'red', 'yellow', 'blue'];

        $base = new Imanee();
        $drawer = new Drawer();

        $drawer->setFont($font)
            ->setFontSize(40);

        foreach ($colors as $color) {
            $drawer->setFontColor($color);
            $frame = Imanee::textGen($text, $drawer);
            $base->addFrame($frame);
        }

        echo $base->animate();
