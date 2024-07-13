<?php
session_start();
$im = imagecreatefrompng('\\canvas.png');
$src1 = imagecreatefrompng("$canvas[1].png");
$src2 = imagecreatefrompng("$chars[2].png");
$src3 = imagecreatefrompng("$chars[3].png");
$src4 = imagecreatefrompng("$chars[4].png");
$src5 = imagecreatefrompng("$chars[5].png");


//https://hibbard.eu/how-to-make-a-simple-visitor-counter-using-php/