Resizing Images
===============

Resizing images with Imanee is pretty straightforward. You can either use a bestfit or force the exact width and height for the resizing.

``Imanee Imanee::resize($width, $height, $bestfit = 1)``

Keeping Proportion
------------------

The resize works in a similar way to the ``thumbnail`` method. By default, the image will be resized to fit inside the provided dimensions, keeping its original proportions.

.. code-block:: php

    $res_jpg  = __DIR__ . '/resources/img01.jpg';

    header("Content-type: image/jpg");

    $imanee = new Imanee($res_jpg);

    echo $imanee->resize(300, 300)
            ->output();


Forcing resulting size
----------------------
If you want to force the resulting size to the specified width and height, you need to provide the extra argument ``$bestfit = 0``. Be aware that this might change the image proportions.

.. code-block:: php

    $res_jpg  = __DIR__ . '/resources/img01.jpg';

    header("Content-type: image/jpg");

    $imanee = new Imanee($res_jpg);

    echo $imanee->resize(300, 300, 0)
            ->output();
