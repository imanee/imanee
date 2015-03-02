Saving Images to Disk
=====================

Saving images to disk is done with the ``write`` method.

``Imanee Imanee::write($path, $jpeg_quality = null)``

The format will be decided based on the extension used for the filename. The second parameter is only used for JPG files, and indicates the quality, up to 100 (100 = no compression, higher quality and bigger file).

Examples
--------

Saving as JPG:

.. code-block:: php

    $res_jpg = __DIR__ . '/../resources/img01.jpg';

    header("Content-type: image/jpg");

    $imanee = new Imanee($res_jpg);

    $imanee
    ->thumbnail(300, 300)
    ->write('path/to/save.jpg', 90);


Saving as PNG:

.. code-block:: php

    $res_jpg = __DIR__ . '/../resources/img01.jpg';

    header("Content-type: image/jpg");

    $imanee = new Imanee($res_jpg);

    $imanee
    ->thumbnail(300, 300)
    ->write('path/to/save.png');

