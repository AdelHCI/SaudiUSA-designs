<?php
include 'timeout.php';
if (!isset($_SESSION["user"])) {
    echo "User not logged in";
    exit();
}
$jsonfiles = json_decode($_POST['json']);
$types = json_decode($_POST['types']);

if ($jsonfiles != null) {
    $file = fopen("files.json", "w");
    fwrite($file, json_encode($jsonfiles, JSON_PRETTY_PRINT));
    $file = fopen("types.json", "w");
    fwrite($file, json_encode($types, JSON_PRETTY_PRINT));
    fclose($file);
    $found = FALSE;

    $dirfiles = scandir("./uploads/");
    foreach ($dirfiles as $i) {
        foreach ($jsonfiles as $j) {
            if ($j->name == $i) {
                $found = TRUE;
                break;
            }
        }
        if (!$found) {
            unlink("./uploads/" . $i);
        }
        $found = FALSE;
    }
}
// File name
$filename = $_FILES['file']['name'];
// Valid file extensions
$valid_extensions = array(
    "jpg",
    "jpeg",
    "png"
);

// File extension
$extension = pathinfo($filename, PATHINFO_EXTENSION);

// Check extension
if (in_array(strtolower($extension), $valid_extensions)) {
    // Upload file
    if (list($width, $height) = getimagesize($_FILES["file"]["tmp_name"])) {
        $filename = $_FILES["file"]["tmp_name"];
        $file = "uploads/" . $filename;
        move_uploaded_file($_FILES['file']['tmp_name'], $file);
        $imgRatio = $width / $height;
        $newWidth = 500;
        $newHeight = $newWidth / $imgRatio;

        if ($extension  == "jpeg" || $extension  == "jpg") {
            $thumb = imagecreatetruecolor($newWidth, $newHeight);
            $source = imagecreatefromjpeg($file);
        } else if ($extension == "png") {
            $thumb = imagecreatetruecolor($newWidth, $newHeight);
            $source = imagecreatefrompng($file);
        }
        imagecopyresized($thumb, $source, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
        $newFileName = "thumbs/" . $filename;
        if ($newFileName) {
            imagejpeg($thumb, $newFileName, 80);
        } else if ($newFileName) {
            imagepng($thumb, $newFileName, 8);
        }

        imagedestroy($source);
        imagedestroy($thumb);

        echo 1;
    } else {
        echo 0;
    }
} else {
    echo 0;
}

exit;
