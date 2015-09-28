[![Build Status](https://travis-ci.org/imanee/imanee.svg?branch=master)](https://travis-ci.org/imanee/imanee)
[![Documentation Status](https://readthedocs.org/projects/imanee/badge/?version=latest)](https://readthedocs.org/projects/imanee/?badge=latest)
[![Coverage Status](https://coveralls.io/repos/imanee/imanee/badge.svg?branch=master&service=github)](https://coveralls.io/github/imanee/imanee?branch=master)
[![License](https://img.shields.io/badge/license-MIT-brightgreen.svg)](https://github.com/imanee/imanee/blob/master/LICENSE.md)
[![Join the chat at https://gitter.im/imanee/imanee](https://badges.gitter.im/Join%20Chat.svg)](https://gitter.im/imanee/imanee?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge&utm_content=badge)

Imanee is a simple Image Manipulation library for PHP. 
It provides an easy flow and convenient methods for creating thumbnails, watermarks, text writing, animated gifs and more.

Check [our live demos](http://imanee.io/#demos) for usage examples.
Check [our documentation](http://imanee.readthedocs.org) for detailed instructions.

## Requirements

Imanee requires PHP >= 5.4 , and one of the following image extensions for PHP: **Imagick** or **GD**. It's recommended to use Imagick as it has more features, including animated gifs support (not available with GD).

## Installation
First make sure you have either Imagick or GD installed and enabled on your PHP server. Imanee will try to use GD if Imagick is not found in the system.

You can add Imanee to your project easily through composer:

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

###Forcing GD usage

```php
header("Content-type: image/jpg");

$imanee = new Imanee('path/to/my/image.jpg', new GDResource());
echo $imanee->thumbnail(200, 200)->output();
```

For more (and complete) examples please have a look at the demos repository: <a href="https://github.com/imanee/demos">https://github.com/imanee/demos</a>
