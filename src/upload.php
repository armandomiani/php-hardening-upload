<?php
include('functions.php');

$file = $_FILES['files'];
$uploadedFilename = $file['tmp_name'];
$errors = [];

$receivedContentType = $file['type'];
$extension = buildExtension($receivedContentType);

// check http content-type sent
if (empty($extension)) {
    $errors[] = "Invalid Content-Type received.";
    abort($errors);
}


$receivedFileSize = $file['size'];
$newFilename = sprintf('%s.%s', time(), $extension);
$destinationFolder = '/app/uploads';
$destinationFile = sprintf('%s/%s', $destinationFolder, $newFilename);

// check header
// Check byte-zero
// check file type
// check file size

$imageInfo = getimagesize($uploadedFilename);

// check real mimetype
if (!in_array($imageInfo['mime'], allowedMimetypes())) {
    $errors[] = "Mocking the file type, huh?";
    abort($errors);
}

// check file size
if (filesize($uploadedFilename) > (1000 * 1024)) {
    $errors[] = "1MB is enough, don't you think?";
    abort($errors);
}

// check file contents
if (checkFileContents($uploadedFilename)) {
    $errors[] = "Are you really trying to do that?";
    abort($errors);
}

# finally receive the file, if no errors has been raised.
if (sizeof($errors) == 0) {    
    move_uploaded_file($uploadedFilename, $destinationFile);

    createImageVersion($newFilename, 1, 'original');
    createImageVersion($newFilename, 0.8, 'large');
    createImageVersion($newFilename, 0.5, 'medium');
    createImageVersion($newFilename, 0.2, 'small');
    
    $_SESSION["uploadedFiles"][] = $newFilename;
    abort([]);
} else {
    abort($errors);
}
?>