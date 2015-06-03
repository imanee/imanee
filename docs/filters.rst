Using Filters
=============

Filters can be used to apply effects to images. There are a limited number of filters already included, but you can create your own filters by implementing the *FilterInterface*. This page covers the general usage of filters.

The result of filters might be slightly different depending on the extension you are using. The black and white effect on Imagick, for instance, is usually darker than the one applied via GD.

Applying Filters
----------------

``applyFilter(string $filter, array $options = [])``

The applyFilter method expects as mandatory parameter the filter name to be applied, and it can also receive an array of options - the options will be
different from filter to filter. All filters should have default values to these options.

The following example applies one of the included filters - black and white - to the image:

.. code-block:: php

    header("Content-type: image/jpg");
    $imanee = new Imanee(__DIR__ . '/img01.jpg');

    echo $imanee->applyFilter('filter_bw')
        ->output();

Built-in Filters
----------------

filter_bw
#########
Applies a black and white effect

.. code-block:: php

    echo $imanee->applyFilter('filter_bw')
        ->output();

filter_sepia
############
Applies a sepia tone effect

.. code-block:: php

    echo $imanee->applyFilter('filter_sepia')
        ->output();

filter_color
############
Applies a color filter, you can define the color

.. code-block:: php

    echo $imanee->applyFilter('filter_color', ['color' => 'red'])
        ->output();

filter_modulate
###############
Changes brightness, contrast and saturation

.. code-block:: php

    echo $imanee->applyFilter(
        'filter_modulate', [
            'brightness' => 100,
            'saturation' => 50,
            'hue'        => 100,
        ]
    )->output();


The built-in filters are just to give you an idea of how it works, and provide some basic effects. The main idea of using filters is to create your own custom filters.
Check our documentation page - `Creating a custom Filter <http://docs.imanee.io/en/latest/customFilter.html>`_.