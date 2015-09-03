Rotating images
===========================

Use the method ``rotate`` to rotates the image resource in the given degrees clockwise:

.. code-block:: php

    $res_jpg = __DIR__ . '/../resources/img01.jpg';

    header("Content-type: image/jpg");
    $imanee = new Imanee($res_jpg);

    $imanee->rotate(30,'#4ACAA8');

    echo $imanee->output();

The first parameter represent the amount of degrees to rotate the image. Negative values will rotate the image anti-clockwise
The second parameter decide of the background color to use to fill the empty spaces, default is transparent.
Allowed value are documented `in the php documentation <http://php.net/manual/en/imagickpixel.construct.php>`_
It will render as black for jpg format (use png if you want it transparent)

.. image:: img/rotated.jpg

.. note::
    It will render as black for jpg format (use png if you want it transparent)
