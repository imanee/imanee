Adding watermarks to images
===========================

``Imanee Imanee::watermark($image, $place_constant = Imanee::IM_POS_BOTTOM_RIGHT, $transparency = 0)``

Use the method ``watermark`` to add watermarks on top of images:

.. code-block:: php

    $res_jpg = __DIR__ . '/../resources/img01.jpg';
    $res_png = __DIR__ . '/../resources/cat01.png';

    header("Content-type: image/jpg");

    $imanee = new Imanee($res_jpg);
    echo $imanee->watermark($res_png, Imanee::IM_POS_BOTTOM_RIGHT, 30)->output();

It's important to remember that only images with alpha channel can have transparency, so if you want to have a translucid watermark you need to use a PNG. Also, the transparency feature is not currently supported by the GD wrapper - it only works well when using the Imagick extension.

.. image:: img/watermark.jpg

.. note::
   The opacity change must be done pixel per pixel, so it will have a poor performance on huge images. You can always save the PNG with transparency already applied, and don't make use of the opacity argument here (should work faster).
