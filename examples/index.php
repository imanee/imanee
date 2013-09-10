<?php

include __DIR__ . '/../vendor/autoload.php';

$res_jpg = __DIR__ . '/resources/img01.jpg';
$res_png = __DIR__ . '/resources/cat01.png';
$res_png2 = __DIR__ . '/resources/cat02.png';

$output = __DIR__ . '/output/output.jpg';

/* imanee load jpg */
header("Content-type: image/jpg");

$imanee = new Imanee\Imanee($res_jpg);
//$imanee = new Imanee\Imanee();


echo $imanee
        ->annotate("testando", 10, 30, 30)
        ->placeText("teste2", 20, \Imanee\Imanee::IM_POS_BOTTOM_CENTER)
        ->placeImage($res_png, \Imanee\Imanee::IM_POS_TOP_RIGHT)
        ->placeImage($res_png2, \Imanee\Imanee::IM_POS_BOTTOM_RIGHT)
        ->setFormat('jpeg')
        ->output();

//echo $imanee->resize(100, 100)->output();

