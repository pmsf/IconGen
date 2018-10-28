<?php

$images = glob('icons/*.png', GLOB_BRACE);
// Create transparant canvas
$img = imagecreatetruecolor(2430, 2511);
$color = imagecolorallocate($img, 255, 255, 255);
imagefill($img, 0, 0, $color);

$x = 0;
$y = 0;
$icons = 0;
foreach ( $images as $k ) {
	$icon = imagecreatefrompng($k);
	imagealphablending($icon, true);
	imagesavealpha($icon, true);
	if ( $x >= 30 ) { 
		$y++;
		$x = 0;
	}
	$left = 81 * $x;
	$top = 81 * $y;
	imagecopymerge($img, $icon, $left, $top, 81, 81, 81, 81, 99);
	$x++;
	$icons++;
}
imagejpeg($img, "previewsheet.jpg");
echo "$icons icons added to previewsheet.png\n";
