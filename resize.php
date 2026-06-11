<?php
$src = 'public/template/Sertifikat_Botania_empty.jpg';
$dest = 'public/template/sertifikat_botania.png';
$img = imagecreatefromjpeg($src);
if (!$img) { die("Could not read image!"); }
$resized = imagescale($img, 1240, 874);
imagepng($resized, $dest, 5);
imagedestroy($img);
imagedestroy($resized);
echo "Done converting PNG.";
