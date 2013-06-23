<?php

include __DIR__ . '/../vendor/autoload.php';

/* imanee new image */
$imanee = new Imanee\Imanee();
$imanee->setSize(640, 480);

/* prepare the drawer */
$drawer = new \Imanee\Drawer();
$drawer->foreground_color = "#000000";
$drawer->font_size = 23;
/*
$imanee->drawText($drawer, 100, 100, "Teste");

$image_content = $imanee->renderAsJpg();

header("Content-type: image/jpeg");
echo $image_content;*/

var_dump($imanee);
echo "just checking.";

/*

$img = (new \Imanee\Imanee())
    ->setSize()
    ->setBackground()
    ->setDrawer((new \Imanee\Drawer())->set('background_color', 0)->set('stroke', 1))
    ->writeText();
*/