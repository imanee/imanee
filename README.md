[![Build Status](https://travis-ci.org/imanee/imanee.svg?branch=master)](https://travis-ci.org/imanee/imanee)
[![Documentation Status](https://readthedocs.org/projects/imanee/badge/?version=latest)](https://readthedocs.org/projects/imanee/?badge=latest)

<p align="center">
  <img src="http://i.imgur.com/cPrVYXY.png" />
</p>

Imanee is a simple wrapper library for Imagemagick on PHP (using the Imagick PHP extension). 
It provides an easy flow and convenient methods for creating thumbnails, watermarks, text writing, animated gifs and more.

Check [our documentation](http://imanee.readthedocs.org) for detailed instructions and usage examples.

## Requirements
Imanee requires the *imagick* PHP extension, and PHP >= 5.4 .

## Installation
Installation can easily be made through composer.

    $ composer require imanee/imanee

## Getting Started

Some simple examples to get you started:

###Thumbnail output

```php
header("Content-type: image/jpg");

$imanee = new Imanee('path/to/my/image.jpg');
echo $imanee->thumbnail(200, 200)->output();
```
###Writing centralized text on top of an image

```php

header("Content-type: image/jpg");

$imanee = new Imanee('path/to/my/image.jpg');
echo $imanee->placeText('imanee test', 40, Imanee::IM_POS_MID_CENTER)
            ->output();
```
                    
###Image composition

```php
header("Content-type: image/jpg");

$imanee = new Imanee('path/to/my/image.jpg');

/** places 4 different png images on the 4 corners of the original image */

echo $imanee->placeImage('img1.png', Imanee::IM_POS_TOP_LEFT)
    ->placeImage('img2.png', Imanee::IM_POS_TOP_RIGHT)
    ->placeImage('img3.png', Imanee::IM_POS_BOTTOM_LEFT)
    ->placeImage('img4.png', Imanee::IM_POS_BOTTOM_RIGHT)
    ->output()
;
```

###Animated Gifs from an array of images

```php
$frames[] = 'img01.png';
$frames[] = 'img02.png';
$frames[] = 'img03.png';
$frames[] = 'img04.png';

header("Content-type: image/gif");

echo Imanee::arrayAnimate($frames, 30);
```

###Animated Gifs from files in a directory
 
```php
header("Content-type: image/gif");

echo Imanee::globAnimate('resources/*.jpg');
```

For more (and complete) examples please have a look at the demos repository: <a href="https://github.com/imanee/demos">https://github.com/imanee/demos</a>
