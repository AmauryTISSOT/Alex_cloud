<?php
// Définissez les dimensions de l'image
$width = 200;
$height = 50;

// Créez une image vide avec les dimensions spécifiées
$image = imagecreatetruecolor($width, $height);

// Définissez les couleurs que vous souhaitez utiliser pour le capcha
$bg_color = imagecolorallocate($image, 255, 255, 255); // Blanc
$text_color = imagecolorallocate($image, 0, 0, 0); // Noir
$graphic_color = imagecolorallocate($image, 64, 64, 64); // Gris foncé

// Remplissez l'image avec la couleur de fond
imagefilledrectangle($image, 0, 0, $width, $height, $bg_color);

// Générez une chaîne aléatoire pour le texte du capcha
$text = generateRandomString();
session_start();
$_SESSION["capcha"]=$text;

// Dessinez le texte sur l'image en utilisant la police par défaut
imagestring($image, 5, 20, 20, $text, $text_color);

// Dessinez des lignes aléatoires sur l'image pour la rendre plus complexe
for ($i = 0; $i < 1; $i++) {
  imageline($image, 0, rand() % $height, $width, rand() % $height, $graphic_color);
}

// Dessinez des points aléatoires sur l'image pour la rendre encore plus complexe
for ($i = 0; $i < 100; $i++) {
  imagesetpixel($image, rand() % $width, rand() % $height, $graphic_color);
}

// Envoyez l'en-tête de l'image au navigateur
header("Content-type: image/png");

// Affichez l'image dans le navigateur
imagepng($image);

// Libérez les ressources utilisées par l'image
imagedestroy($image);

// Cette fonction génère une chaîne aléatoire de caractères pour le texte du capcha
function generateRandomString()
{
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        $length = 10;

        for ($i = 0; $i < $length; $i++)
        {
                $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        return $randomString;
}
