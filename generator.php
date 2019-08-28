<?php

$json = file_get_contents( 'pokemon.json' );
$pokemons = json_decode( $json, true );
$dir = realpath(dirname(__FILE__));
$noassets = array('Shadow', 'Purified', 'No Evolve', 'Fall');
function strposarr ( $nameform, $noassets, $offset=0) {
	if(!is_array($noassets)) $noassets = array($noassets);
		foreach($noassets as $query) {
			if(strpos($nameform, $query, $offset) !== false) return true;
		}
		return false;
}
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
	// Define text font
	$id_font_path = "" . $dir . "/Lato-Black.ttf";
	$name_font_path = "" . $dir . "/Lato-Bold.ttf";
	$form_font_path = "" . $dir . "/Lato-Regular.ttf";
	$font_size = 15;
	$form_font_size = 12;
	$hasforms = isset( $pokemon['forms'] );
	if ( $hasforms ) {
		$i = 1;
		foreach ( $pokemon['forms'] as $f ) {
			// Get form type color from pokemon
			$formcolor = array();
			foreach ( $f['formtypes'] as $ft ) {
				array_push($formcolor, $ft['color']);
			}
			$primaryformcolor = $formcolor[0];
			list($fr, $fg, $fb) = sscanf($primaryformcolor, "#%02x%02x%02x");
			// Create transparant canvas
			$img = imagecreatetruecolor(81, 81);
			$color = imagecolorallocatealpha($img, 0, 0, 0, 127);
			imagefill($img, 0, 0, $color);
			imagealphablending($img, true);
			imagesavealpha($img, true);
			// Define colors
			$white = imagecolorallocate($img, 255, 255, 255);
			$black = imagecolorallocate($img, 0, 0, 0);
			$grey = imagecolorallocate($img, 128, 128, 128);
			$circlecolor = imagecolorallocate($img, $fr, $fg, $fb);
			// Draw colored circle
			imagefilledellipse($img, 40, 40, 80, 80, $circlecolor);
			imageellipse($img, 40, 40, 80, 80, $black);
			$protoform = $f['protoform'];
			if ( strposarr ( $f['nameform'], $noassets ) ) { 
				$assetform = rand(4000, 9999);
			} else {
				$assetform = $f['assetsform'];
			}
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

			$formbbox = imagettfbbox($form_font_size, 0, $form_font_path, $nameform);
			$form_image_width = abs($formbbox[4] - $formbbox[0]);
			if ( $form_image_width <= 80 ) {
				$form_x = floor((80 - $form_image_width) / 2 + 2);
			} else {
				$form_font_size = 15 - 4;
				$formbbox = imagettfbbox($form_font_size, 0, $form_font_path, $nameform);
				$form_image_width = abs($formbbox[4] - $formbbox[0]);
				$form_x = floor((80 - $form_image_width) / 2 + 2);
			}

			imagettftext($img, 20, 0, 17, 27, $grey, $id_font_path, $id);
			imagettftext($img, 20, 0, 16, 26, $white, $id_font_path, $id);
			imagettftext($img, $font_size, 0, $name_x + 1, 49, $grey, $name_font_path, $pokemon['name']);
			imagettftext($img, $font_size, 0, $name_x, 48, $white, $name_font_path, $pokemon['name']);
			imagettftext($img, $form_font_size, 0, $form_x + 1, 66, $grey, $form_font_path, $nameform);
			imagettftext($img, $form_font_size, 0, $form_x, 65, $white, $form_font_path, $nameform);
			imagepng($img, "icons/pokemon_icon_" . $id . "_" . $protoform . ".png");
			imagepng($img, "icons/pokemon_icon_" . $id . "_" . $assetform . ".png");
			$pokemonname = $pokemon['name'];
			echo "Icon for id: $id name: $pokemonname writen as $nameform. File pokemon_icon_" . $id . "_" . $protoform . ".png\n";
			echo "Icon for id: $id name: $pokemonname writen as $nameform. File pokemon_icon_" . $id . "_" . $assetform . ".png\n";
			if ( $i <= 1 ) {
				imagepng($img, "icons/pokemon_icon_" . $id . "_00.png");
				echo "Icon for id: $id name: $pokemonname writen as normal. File pokemon_icon_" . $id . "_00.png\n";
			}
			$i++;
		}
	} else {
		// Create transparant canvas
		$img = imagecreatetruecolor(81, 81);
		$color = imagecolorallocatealpha($img, 0, 0, 0, 127);
		imagefill($img, 0, 0, $color);
		imagealphablending($img, true);
		imagesavealpha($img, true);
		// Define colors
		$white = imagecolorallocate($img, 255, 255, 255);
		$black = imagecolorallocate($img, 0, 0, 0);
		$circlecolor = imagecolorallocate($img, $r, $g, $b);
		$grey = imagecolorallocate($img, 128, 128, 128);
		// Draw colored circle
		imagefilledellipse($img, 40, 40, 80, 80, $circlecolor);
		imagesetthickness($img, 2);
		imageellipse($img, 40, 40, 80, 80, $black);
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
		imagettftext($img, 20, 0, 17, 27, $grey, $id_font_path, $id);
		imagettftext($img, 20, 0, 16, 26, $white, $id_font_path, $id);
		imagettftext($img, $font_size, 0, $x + 1, 49, $grey, $name_font_path, $pokemon['name']);
		imagettftext($img, $font_size, 0, $x, 48, $white, $name_font_path, $pokemon['name']);
		imagepng($img, "icons/pokemon_icon_" . $id . "_00.png");
		$pokemonname = $pokemon['name'];
	        echo "Icon for id: $id name: $pokemonname writen as normal. File pokemon_icon_" . $id . "_00.png\n";
	}
}
