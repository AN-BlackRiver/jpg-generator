<?php
require_once __DIR__ . '/Db.php';

$name = $_GET['name'] ?? null;
$size = $_GET['size'] ?? null;

if (!$name || !$size) {
    http_response_code(400);
    exit("Необходимо указать параметры 'name' и 'size'.");
}

if (!preg_match('/^[a-zA-Z0-9_-]+$/', $name)) {
    http_response_code(400);
    exit("Недопустимое имя файла.");
}

$originalPath = __DIR__ . "/gallery/$name.jpg";
if (!file_exists($originalPath)) {
    http_response_code(404);
    exit("Изображение не найдено.");
}

try {
    $db = Db::getInstance();
    $dimensions = $db->fetchOne(
        "SELECT width, height FROM image_sizes WHERE size_code = ?",
        [$size]
    );
    $db->close();
} catch (Throwable $e) {
    http_response_code(500);
    exit("Ошибка подключения к базе данных.");
}

if (!$dimensions) {
    http_response_code(400);
    exit("Недопустимый размер '$size'.");
}

$maxWidth = (int)$dimensions['width'];
$maxHeight = (int)$dimensions['height'];

$cachePath = __DIR__ . "/cache/{$name}_{$size}.jpg";

if (file_exists($cachePath)) {
    header('Content-Type: image/jpeg');
    readfile($cachePath);
    exit;
}

[$originalWidth, $originalHeight] = getimagesize($originalPath);

$ratio = min($maxWidth / $originalWidth, $maxHeight / $originalHeight);
$newWidth = max(1, (int)($originalWidth * $ratio));
$newHeight = max(1, (int)($originalHeight * $ratio));

$srcImage = imagecreatefromjpeg($originalPath);
$dstImage = imagecreatetruecolor($newWidth, $newHeight);

imagecopyresampled(
    $dstImage, $srcImage,
    0, 0, 0, 0,
    $newWidth, $newHeight,
    $originalWidth, $originalHeight
);

imagejpeg($dstImage, $cachePath, 90);

header('Content-Type: image/jpeg');
imagejpeg($dstImage);

imagedestroy($srcImage);
imagedestroy($dstImage);
