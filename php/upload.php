<?php
include 'timeout.php';
if(!isset($_SESSION["user"])){
echo 0;
exit();
}
$jsonfiles = json_decode($_POST['json']);
if($jsonfiles){
    $file = fopen("files.json", "w");
    fwrite($file, $_POST['json']);
    fclose($file);
}
if ($jsonfiles != null) {
    $file = fopen("files.json", "w");
    fwrite($file, json_encode($jsonfiles));
    fclose($file);
    //echo nl2br("upload files: ".json_encode($jsonfiles) . "\n");
$found = FALSE;
    foreach ($dirfiles as $i) {
foreach ($jsonfiles as $j) {
            if ($j->name == $i) {

echo $i . " == ". $j->name;

                $found = TRUE;
                break;
            }
        }
        if (!$found){

            unlink("./uploads/" . $i);
		echo $found." ".$i." deleted.";
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
    if (move_uploaded_file($_FILES['file']['tmp_name'], "uploads/" . $filename)) {
        echo 1;
    } else {
        echo 0;
    }
} else {
    echo 0;
}

exit;
?>
