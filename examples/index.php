<?php

include __DIR__ . '/../vendor/autoload.php';

$iman = new Imanee\Imanee();

$img = new \Imanee\Drawer();

$img->background_color = "teste";

var_dump($img);

echo "just checking.";

/*

$img = (new \Imanee\Imanee())
    ->setSize()
    ->setBackground()
    ->setDrawer((new \Imanee\Drawer())->set('background_color', 0)->set('stroke', 1))
    ->writeText();
*/