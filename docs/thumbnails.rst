Creating Thumbnails
===================

``Imanee Imanee::thumbnail($width, $height, $crop = false)``

1. Creating a thumbnail to fit an area
--------------------------------------

The ``thumbnail`` method will generate a proportional thumbnail in a size that can fit in the specified dimensions::

    $res_jpg = __DIR__ . '/../resources/img01.jpg';

    header("Content-type: image/jpg");

    $imanee = new Imanee($res_jpg);

    echo $imanee->thumbnail(300, 300)
                ->output();


2. Creating a cropped thumbnail
-------------------------------

The ``thumbnail`` method can receive an additional argument that will create the thumbnail using a strict size, by cropping the image to keep proportion.
Crop is centered.::

    $res_jpg = __DIR__ . '/../resources/img01.jpg';

    header("Content-type: image/jpg");

    $imanee = new Imanee($res_jpg);

    echo $imanee->thumbnail(300, 200, true)
                ->output();

In order to create a square thumbnail, you just need to provide same width and height and the additional ``crop = true`` argument::

    $res_jpg = __DIR__ . '/../resources/img01.jpg';

    header("Content-type: image/jpg");

    $imanee = new Imanee($res_jpg);

    echo $imanee->thumbnail(300, 300, true)
                ->output();

