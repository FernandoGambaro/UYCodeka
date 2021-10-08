<?php
$url = '';
if(array_key_exists('url', $_GET)
   and filter_var($_GET['url'], FILTER_VALIDATE_URL)) {
     $url = $_GET['url'];
}
header('Content-Type: image/png');
header('Content-Disposition: attachment; filename="chart.png"');
$image = file_get_contents('$url');
header('Content-Length: ' . strlen($image));
echo $image;
?>