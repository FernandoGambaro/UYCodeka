<?php
require(dirname(__FILE__).'/../classes/Encryption.php');

session_start(); # iniciamos la sesion


$numero = rand(11121,99999); # generamos un numero aleatorio

$converter = new Encryption;
$captcha= $converter->encode($numero);
$_SESSION['codigo'] = $captcha; # guardamos el numero en una variable de sesión


header("Content-type: image/png");
header('Cache-Control: no-cache');

# declaramos im con la creación de una imagen
//$im = imagecreate(180, 125);

// Create an image from button.png
$image= imagecreatefrompng('../library/images/bklogin.png');
// Set the font colour
$colour = imagecolorallocate($image, 183, 170, 152);

// Set the font
$font = '../library/fonts/ttf/Diskontented.ttf';

// Set the font colour
$colour = imagecolorallocate($image, 110, 110, 110);
// Set a random integer for the rotation between -15 and 25 degrees
$rotate = rand(-15, 25);
// Set a random integer for position
$postition = rand(15,65);
// Create an image using our original image and adding the detail
imagettftext($image, 20, $rotate, $postition, 34, $colour, $font, $numero);
// Output the image as a png
imagepng($image);
?>