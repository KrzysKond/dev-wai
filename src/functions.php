<?php
function applyWatermark($originalImagePath, $watermarkText, $outputImagePath) {
    $image = imagecreatefromstring(file_get_contents($originalImagePath));
    $white = imagecolorallocate($image, 255, 255, 255);


    $fontSize = 20;
    $textAngle = 45;
    $textX = 20;
    $textY =200;


    imagettftext($image, $fontSize, $textAngle, $textX, $textY, $white, 'static/fonts/Roboto-Regular.ttf', $watermarkText);

    imagejpeg($image, $outputImagePath);

}

function createThumbnail($originalImagePath, $outputImagePath){
    $width = 200;
    $height = 125;

    $imageInfo = getimagesize($originalImagePath);
    $imageType = $imageInfo[2];


    switch ($imageType) {
        case IMAGETYPE_JPEG:
            $originalImage = imagecreatefromjpeg($originalImagePath);
            break;
        case IMAGETYPE_PNG:
            $originalImage = imagecreatefrompng($originalImagePath);
            break;
        default:
            return false;
    }


    $thumbnail = imagecreatetruecolor($width, $height);


    imagecopyresampled($thumbnail, $originalImage, 0, 0, 0, 0, $width, $height, imagesx($originalImage), imagesy($originalImage));


    switch ($imageType) {
        case IMAGETYPE_JPEG:
            imagejpeg($thumbnail, $outputImagePath);
            break;
        case IMAGETYPE_PNG:
            imagepng($thumbnail, $outputImagePath);
            break;
        default:
            return false;
    }


    imagedestroy($thumbnail);
    imagedestroy($originalImage);

    return true;
}

function validateImage($file) {
    $allowedFormats = ['image/png', 'image/jpg'];
    $maxFileSize = 1000000;

    if ($file['error'] !== UPLOAD_ERR_OK) {
        return "Błąd podczas przesyłania pliku.";
    }

    $fileType = mime_content_type($file['tmp_name']);
    if (!in_array($fileType, $allowedFormats)) {
        return "Dozwolone są tylko pliki PNG i JPG.";
    }

    if ($file['size'] > $maxFileSize) {
        return "Plik przekracza maksymalny rozmiar (1 MB).";
    }

    return 'ok';
}