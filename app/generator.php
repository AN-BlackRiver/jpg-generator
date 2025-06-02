<?php
require_once __DIR__ . '/Db.php';

if (!isset($_GET['name']) || !isset($_GET['size'])) {
    die("Необходимо указать параметры 'name' и 'size'.");
}

$name = $_GET['name'];
$size = $_GET['size'];

$originalPath = __DIR__ . "/gallery/$name.jpg";
if (!file_exists($originalPath)) {
    die("Изображение не найдено.");
}

$db = Db::getInstance();

$dimensions = $db->fetchOne("SELECT width, height FROM image_sizes WHERE size_code = ?", [$size]);

$db->close();

if (!$dimensions) {
    die("Недопустимый размер '$size'.");
}

$maxWidth = $dimensions['width'];
$maxHeight = $dimensions['height'];

$cachePath = __DIR__ . "/cache/{$name}_{$size}.jpg";

if (file_exists($cachePath)) {
    header('Content-Type: image/jpeg');
    readfile($cachePath);
    exit;
}

list($originalWidth, $originalHeight) = getimagesize($originalPath);

$ratio = min($maxWidth / $originalWidth, $maxHeight / $originalHeight);
$newWidth = intval($originalWidth * $ratio);
$newHeight = intval($originalHeight * $ratio);

$image = imagecreatefromjpeg($originalPath);
$resizedImage = imagecreatetruecolor($newWidth, $newHeight);

imagecopyresampled(
    $resizedImage,
    $image,
    0, 0, 0, 0,
    $newWidth, $newHeight,
    $originalWidth, $originalHeight
);

imagejpeg($resizedImage, $cachePath, 90);

header('Content-Type: image/jpeg');
imagejpeg($resizedImage);

imagedestroy($image);
imagedestroy($resizedImage);
?>