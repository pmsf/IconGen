<?php

$images = glob('icons/*.png', GLOB_BRACE);
// Create transparant canvas
$img = imagecreatetruecolor(2430, 2511);
$color = imagecolorallocatealpha($img, 0, 0, 0, 127);
imagefill($img, 0, 0, $color);
imagesavealpha($img, true);
imagealphablending($img, false);

$x = 0;
$y = 0;
$icons = 0;
foreach ( $images as $k ) {
	$icon = imagecreatefrompng($k);
	imagealphablending($icon, false);
	imagesavealpha($icon, true);
	if ( $x >= 30 ) { 
		$y++;
		$x = 0;
	}
	$left = 81 * $x;
	$top = 81 * $y;
	imagecopy($img, $icon, $left, $top, 81, 81, 81, 81);
	$x++;
	$icons++;
}
imagepng($img, "previewsheet.png");
echo "$icons icons added to previewsheet.png\n";
