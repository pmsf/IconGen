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

	$primarycolor = $color[0];
	list($r, $g, $b) = sscanf($primarycolor, "#%02x%02x%02x");
	$img = imagecreatetruecolor(80, 80);
	imagesavealpha($img, true);
	$color = imagecolorallocatealpha($img, 0, 0, 0, 127);
	$circlecolor = imagecolorallocate($img, $r, $g, $b);
	imagefill($img, 0, 0, $color);
	imagefilledellipse($img, 40, 40, 80, 80, $circlecolor);
	$font_path = '/opt/safespritesgenerator/Aller_Rg.ttf';
	$white = imagecolorallocate($img, 255, 255, 255);

	$hasforms = isset( $pokemon['forms'] );
	if ( $hasforms ) {
		foreach ( $pokemon['forms'] as $f ) {
			$protoform = $f['protoform'];
			imagettftext($img, 20, 0, 18, 23, $white, $font_path, $id);
			imagettftext($img, 15, 0, 1, 46, $white, $font_path, $pokemon['name']);
			imagepng($img, "icons/pokemon_icon_" . $id . "_" . $protoform . ".png");
			imagettftext($img, 15, 0, 1, 69, $white, $font_path, $f['nameform']);
		}
	} else {
		imagettftext($img, 20, 0, 18, 23, $white, $font_path, $id);
		imagettftext($img, 15, 0, 1, 46, $white, $font_path, $pokemon['name']);
		imagepng($img, "icons/pokemon_icon_" . $id . "_00.png");
	}




	if ( $k > 1 ) {
		break;
	}
}
