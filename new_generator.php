<?php
if (!php_sapi_name() == "cli") {
    echo "Script can only be run through CLI";
}
$extenstions = get_loaded_extensions();
if (!extension_loaded('imagick')) {
      echo "PHP extension imagick not loaded";
}
if (!extension_loaded('gd')) {
      echo "PHP extension gd not loaded";
}

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
$pokemons[0] = [
    "name" => "MissingNo",
    "types" => [
        [
            "type" => "Normal",
            "color" => "#8a8a59"
        ]
    ]
];
foreach ( $pokemons as $id => $pokemon ) {
      $id = $id > 0 ? $id : "0";
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
                  $greyX = $id > 99 ? 17 : ($id > 9 ? 27 : 34);
                  $whiteX = $id > 99 ? 16 : ($id > 9 ? 26 : 33);
                  imagettftext($img, 20, 0, $greyX, 27, $grey, $id_font_path, $id);
                  imagettftext($img, 20, 0, $whiteX, 26, $white, $id_font_path, $id);
                  imagettftext($img, $font_size, 0, $name_x + 1, 49, $grey, $name_font_path, $pokemon['name']);
                  imagettftext($img, $font_size, 0, $name_x, 48, $white, $name_font_path, $pokemon['name']);
                  imagettftext($img, $form_font_size, 0, $form_x + 1, 66, $grey, $form_font_path, $nameform);
                  imagettftext($img, $form_font_size, 0, $form_x, 65, $white, $form_font_path, $nameform);
                  imagepng($img, "uicons/" . $id . "_f" . $protoform . ".png");
                  $pokemonname = $pokemon['name'];
                  echo "Icon for id: $id name: $pokemonname writen as $nameform. File " . $id . "_f" . $protoform . ".png\n";
                  if ( $i <= 1 ) {
                        imagepng($img, "uicons/" . $id . ".png");
                        echo "Icon for id: $id name: $pokemonname writen as normal. File " . $id . ".png\n";
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
            $greyX = $id > 99 ? 17 : ($id > 9 ? 27 : 34);
            $whiteX = $id > 99 ? 16 : ($id > 9 ? 26 : 33);
            imagettftext($img, 20, 0, $greyX, 27, $grey, $id_font_path, $id);
            imagettftext($img, 20, 0, $whiteX, 26, $white, $id_font_path, $id);
            imagettftext($img, $font_size, 0, $x + 1, 49, $grey, $name_font_path, $pokemon['name']);
            imagettftext($img, $font_size, 0, $x, 48, $white, $name_font_path, $pokemon['name']);
            imagepng($img, "uicons/" . $id . ".png");
            $pokemonname = $pokemon['name'];
              echo "Icon for id: $id name: $pokemonname writen as normal. File " . $id . ".png\n";
      }
}
$thisFolder = dirname(__FILE__) . DIRECTORY_SEPARATOR . "uicons" . DIRECTORY_SEPARATOR;
/* Create master json file */
file_put_contents($thisFolder . 'index.json', json_encode(dirtree($thisFolder)));

function dirtree($dir, $ignoreEmpty=false) {
    if (!$dir instanceof DirectoryIterator) {
        $dir = new DirectoryIterator((string)$dir);
    }
    $dirs  = array();
    $files = array();
    foreach ($dir as $node) {
        if ($node->isDir() && !$node->isDot()) {
            $tree = dirtree($node->getPathname(), $ignoreEmpty);
            if (!$ignoreEmpty || count($tree)) {
                $dirs[$node->getFilename()] = $tree;
            }
        } elseif ($node->isFile()) {
            $name = $node->getFilename();
            if (!str_ends_with($name, '.json') && !str_ends_with($name, '.php')) {
                $files[] = $name;
            }
        }
    }
    asort($dirs);
    sort($files);

    return array_merge($dirs, $files);
}
