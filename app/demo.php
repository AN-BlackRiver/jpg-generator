<?php
require_once __DIR__ . '/Db.php';

$db = Db::getInstance();

$galleryDir = __DIR__ . '/gallery';
$images = glob("$galleryDir/*.jpg");

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Галерея</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.css">
    <style>
        .gallery {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }
        .gallery a {
            display: block;
            position: relative;
            overflow: hidden;
            width: 200px;
            height: 200px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }
        .gallery a:hover {
            transform: scale(1.05);
        }
        .gallery img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            cursor: pointer;
        }
    </style>
</head>
<body>
<div class="gallery">
    <?php foreach ($images as $image): ?>
        <?php
        $imageName = basename($image, '.jpg');
        $previewUrl = "generator.php?name=$imageName&size=min";
        $fullSizeUrl = "generator.php?name=$imageName&size=big";
        ?>
        <a href="<?php echo $fullSizeUrl; ?>" data-fancybox="gallery" data-caption="<?php echo $imageName; ?>">
            <img src="<?php echo $previewUrl; ?>" alt="<?php echo $imageName; ?>">
        </a>
    <?php endforeach; ?>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.js"></script>

<script>
    $(document).ready(function() {
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