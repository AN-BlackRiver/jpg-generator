<?php
require_once __DIR__ . '/Db.php';

$db = Db::getInstance();

$galleryDir = __DIR__ . '/gallery';
$images = glob("$galleryDir/*.jpg");

function e(string $str): string {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Галерея</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.css">
    <style>
        body {
            font-family: sans-serif;
            padding: 20px;
            background: #f5f5f5;
        }
        .gallery {
            display: flex;
            flex-wrap: wrap;
            gap: 16px;
            justify-content: center;
        }
        .gallery a {
            display: block;
            width: 300px;
            height: 200px;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 6px 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .gallery a:hover {
            transform: scale(1.03);
            box-shadow: 0 8px 14px rgba(0, 0, 0, 0.15);
        }
        .gallery img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }
        .header {
            text-align: center;
        }
    </style>
</head>
<body>

<h1 class="header">Галерея изображений</h1>
<div class="gallery">
    <?php foreach ($images as $image): ?>
        <?php
        $imageName = pathinfo($image, PATHINFO_FILENAME);
        $previewUrl = "generator.php?name=" . urlencode($imageName) . "&size=min";
        $fullSizeUrl = "generator.php?name=" . urlencode($imageName) . "&size=big";
        ?>
        <a href="<?= e($fullSizeUrl) ?>" data-fancybox="gallery" data-caption="<?= e($imageName) ?>">
            <img src="<?= e($previewUrl) ?>" alt="<?= e($imageName) ?>">
        </a>
    <?php endforeach; ?>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" defer></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.js" defer></script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        $('[data-fancybox="gallery"]').fancybox({
            buttons: [
                "zoom",
                "share",
                "slideShow",
                "fullscreen",
                "download",
                "thumbs",
                "close"
            ],
            animationEffect: "fade",
            transitionEffect: "slide",
            loop: true,
            thumbs: {
                autoStart: true
            }
        });
    });
</script>
</body>
</html>
