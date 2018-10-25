<?php

$json = file_get_contents( 'pokemon.json' );
$pokemons = json_decode( $json, true );

foreach ( $pokemons as $k => $pokemon ) {
	if ( $k <= 9 ) {
		$id = "00$k";
	} else if ( $k <= 99 ) {
		$id = "0$k";
	} else {
		$id = $k;
	}

	$color = array();
	foreach ( $pokemon['types'] as $t ) {
		array_push($color, $t['color']);
	}
	// Get type color from pokemon
	$primarycolor = $color[0];
	list($r, $g, $b) = sscanf($primarycolor, "#%02x%02x%02x");
	// Create transparant canvas
	$img = imagecreatetruecolor(80, 80);
	$color = imagecolorallocatealpha($img, 0, 0, 0, 127);
	imagefill($img, 0, 0, $color);
	imagesavealpha($img, true);
	// Define colors
	$white = imagecolorallocate($img, 255, 255, 255);
	$black = imagecolorallocate($img, 0, 0, 0);
	$circlecolor = imagecolorallocate($img, $r, $g, $b);
	// Draw colored circle
	imagefilledellipse($img, 40, 40, 80, 80, $circlecolor);
	// Define text font
	$id_font_path = '/opt/safespritesgenerator/Lato-Black.ttf';
	$name_font_path = '/opt/safespritesgenerator/Lato-Bold.ttf';
	$form_font_path = '/opt/safespritesgenerator/Lato-Regular.ttf';
	$font_size = 15;
	$form_font_size = 12;
	$hasforms = isset( $pokemon['forms'] );
	if ( $hasforms ) {
		foreach ( $pokemon['forms'] as $f ) {
			$protoform = $f['protoform'];
			$nameform = $f['nameform'];
			$namebbox = imagettfbbox($font_size, 0, $name_font_path, $pokemon['name']);
			$name_image_width = abs($namebbox[4] - $namebbox[0]);
			if ( $name_image_width <= 80 ) {
				$name_x = floor((80 - $name_image_width) / 2 + 2);
			} else {
				$font_size = 15 - 4;
				$namebbox = imagettfbbox($font_size, 0, $name_font_path, $pokemon['name']);
				$name_image_width = abs($namebbox[4] - $namebbox[0]);
				$name_x = floor((80 - $name_image_width) / 2 + 2);
			}

			$formbbox = imagettfbbox($font_size, 0, $form_font_path, $pokemon['name']);
			$form_image_width = abs($formbbox[4] - $formbbox[0]);
			if ( $form_image_width <= 80 ) {
				$form_x = floor((80 - $form_image_width) / 2 + 10);
			} else {
				$form_font_size = 15 - 4;
				$formbbox = imagettfbbox($font_size, 0, $form_font_path, $pokemon['name']);
				$form_image_width = abs($formbbox[4] - $formbbox[0]);
				$form_x = floor((80 - $form_image_width) / 2 + 10);
			}

			imagettftext($img, 20, 0, 16, 26, $white, $id_font_path, $id);
			imagettftext($img, $font_size, 0, $name_x, 48, $white, $name_font_path, $pokemon['name']);
			imagettftext($img, $form_font_size, 0, $form_x, 65, $white, $form_font_path, $nameform);
			imagepng($img, "icons/pokemon_icon_" . $id . "_" . $protoform . ".png");
		}
	} else {
		$bbox = imagettfbbox($font_size, 0, $name_font_path, $pokemon['name']);
		$image_width = abs($bbox[4] - $bbox[0]);
		if ( $image_width <= 80 ) {
			$x = floor((80 - $image_width) / 2 + 2);
		} else {
			$font_size = 15 - 4;
			$bbox = imagettfbbox($font_size, 0, $name_font_path, $pokemon['name']);
			$image_width = abs($bbox[4] - $bbox[0]);
			$x = floor((80 - $image_width) / 2 + 2);
		}
		imagettftext($img, 20, 0, 16, 26, $white, $id_font_path, $id);
		imagettftext($img, $font_size, 0, $x, 48, $white, $name_font_path, $pokemon['name']);
		imagepng($img, "icons/pokemon_icon_" . $id . "_00.png");
	}
}
