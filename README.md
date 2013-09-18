# imanee

Imanee is a simple wrapper library for Imagemagick. It provides an easy flow for editing images and performing common tasks such as thumbnails, watermarks and text writing.
This is an experimental project under development.

## Requirements
Imanee requires the *imagick* PHP extension, and PHP >= 5.4 .

## Installation
Instalation can be made through composer.

<pre><code>
    "require": {
        "imanee/imanee": "dev-master"
    }
</code></pre>

## Usage examples

1. Thumbnail Output

<pre><code>

header("Content-type: image/jpg");

$imanee = new Imanee('path/to/my/image.jpg');
echo $imanee->thumbnail(200, 200)->output();

</code></pre>

2. Image composition

<pre><code>
header("Content-type: image/jpg");

$imanee = new Imanee('path/to/my/image.jpg');

/* places 4 different png images on the 4 corners of the original image */

echo $imanee->placeImage('img1.png', \Imanee\Imanee::IM_POS_TOP_LEFT)
            ->placeImage('img2.png', \Imanee\Imanee::IM_POS_TOP_RIGHT)
            ->placeImage('img3.png', \Imanee\Imanee::IM_POS_BOTTOM_LEFT)
            ->placeImage('img4.png', \Imanee\Imanee::IM_POS_BOTTOM_RIGHT)
            ->output();
</code></pre>

For more (and complete) examples see: <a href="https://github.com/imanee/demos">https://github.com/imanee/demos</a>