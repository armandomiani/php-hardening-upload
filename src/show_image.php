<?php
include('functions.php');

$isFileNameValid = checkBadString($_GET['file']);
if (!$isFileNameValid) {
    $_SESSION['errors'] = [
        'Bad bad guy....'
    ];
    header('Location: file_list.php');
}

$file = decrypt($_GET['file']);
if (!$file) {
    $_SESSION['errors'] = [
        'hmm.... not this cipher... you can do it :D'
    ];
    header('Location: file_list.php');
}

$uploadPath = getUploadFolderPath();
$filePath = sprintf('%s/%s', $uploadPath, $file);
$imageInfo = getimagesize($filePath);
outputImage($filePath, $imageInfo['mime']);
?>