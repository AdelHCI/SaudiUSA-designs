<?php
include 'timeout.php';
if (!isset($_SESSION["user"])) {
    echo "Not logged in.";
    exit();
}
$valid_extensions = array(
    "jpg",
    "jpeg",
    "png"
);

$dirfiles = scandir("./uploads");
foreach ($dirfiles as $i) {
    $extension = pathinfo("uploads/" . $i, PATHINFO_EXTENSION);
    if (in_array(strtolower($extension), $valid_extensions)) {
        $file = "uploads/" . $i;
        $imgInfo = getimagesize($file);
        $width = $imgInfo[0];
        $height = $imgInfo[1];
        $imgRatio = $width / $height;
        $newWidth = 800;
        $newHeight = $newWidth / $imgRatio;

        if ($extension  == "jpeg" || $extension  == "jpg") {
            $thumb = imagecreatetruecolor($newWidth, $newHeight);
            $source = imagecreatefromjpeg($file);
        } else if ($extension == "png") {
            $thumb = imagecreatetruecolor($newWidth, $newHeight);
            $source = imagecreatefrompng($file);
        }

        imageinterlace($thumb, true);
        imagecopyresized($thumb, $source, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

        $newFileName = "thumbs/" . $i;
        echo $newFileName;
        if ($newFileName) {
            imagejpeg($thumb, $newFileName, 80);
        } else if ($newFileName) {
            imagepng($thumb, $newFileName, 8);
        }


        imagedestroy($source);
        imagedestroy($thumb);
    }
}

exit;
