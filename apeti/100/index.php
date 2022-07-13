<?php
  header('Content-Type: image/png');

  $token_id = $_GET['id'];
  $path = "../200/$token_id.png";      
  $img = imagecreatefrompng($path);
  $im = imagescale($img , 1233, 68); // 1233x68
  imagealphablending($im, false);
  imagesavealpha($im, true);

  imagepng($im);
  imagedestroy($im);
?>
