<?php
session_start();
// ini_set('display_errors', 'off');

function buildExtension($contentType) {
    $contentType = trim($contentType);
    if ($contentType == 'image/png')
        return 'png';
    if ($contentType == 'image/jpeg' || $contentType == 'image/jpg')
        return 'jpg';
    return '';
}

function allowedMimetypes() {
    return [
        "image/png",
        "image/jpeg",
        "image/jpg"
    ];
}

function getUploadFolderPath() {
    return '/app/uploads';
}

function abort($errors) {
    $_SESSION['errors'] = $errors;
    $valid = sizeof($_SESSION['errors']) == 0;

    echo json_encode([
        "valid" => $valid
    ]);
    die();
    exit;
}

function checkBadString($content) {
    $bads = [
        "php:",
        "<?php",
        "passth",
        "exec",
        "system",
        "..",
        "ls",
        "cat",
        "function",
    ];

    foreach ($bads as $bad) {
        if (strpos($content, $bad) > 0)
            return false;
    }

    return true;
}

function checkFileContents($filePath) {
    $f = fopen($filePath,'r');
    $content="";    
    while(!feof($f))
    {
        $content .= fgets($f);
    }
    
    return checkBadString($content);
}

function readKey() {
    return file_get_contents('/keys/sodkey');
}

function encrypt($text) {
    $key = readKey();
    $nonce = random_bytes(SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);
    $ciphertext = sodium_crypto_secretbox($text, $nonce, $key);
    return base64_encode($nonce . $ciphertext);
}

function decrypt($text) {
    $key = readKey();
    $decoded = base64_decode($text);
    $nonce = mb_substr($decoded, 0, SODIUM_CRYPTO_SECRETBOX_NONCEBYTES, '8bit');
    $ciphertext = mb_substr($decoded, SODIUM_CRYPTO_SECRETBOX_NONCEBYTES, null, '8bit');
    return sodium_crypto_secretbox_open($ciphertext, $nonce, $key);
}

function outputImage($filename, $type) {        
    header('Content-Type:'.$type);
    header('Content-Length: ' . filesize($filename));
    readfile($filename);
}

function humanDate($filename) {
    $parts = explode('.', $filename);
    $ts = $parts[0];

    return date('r', $ts);
}

function createImageAccordingType($filename, $mimeType) 
{
    if ($mimeType == 'image/jpeg' || $mimeType == 'image/jpg')
        return imagecreatefromjpeg($filename);
    if ($mimeType == 'image/png')
        return imagecreatefrompng($filename);
    return false;
}

function saveImageToDisk($image, $fileName, $mimeType) 
{
    $uploadPath = getUploadFolderPath();    
    $destinationFileName = sprintf('%s/%s', $uploadPath, $fileName);

    if ($mimeType == 'image/jpeg' || $mimeType == 'image/jpg')            
        return imagejpeg($image, $destinationFileName);
    
    if ($mimeType == 'image/png')     
        return imagepng($image, $destinationFileName);
    
    return false;
}

function createImageVersion($originalFilename, $percent, $version) {
    $uploadPath = getUploadFolderPath();
    $originalFilePath = sprintf('%s/%s', $uploadPath, $originalFilename);

    // Get new sizes
    $imageInfo = getimagesize($originalFilePath);
    list($width, $height) = $imageInfo;
    $newWidth = $width * $percent;
    $newHeight = $height * $percent;

    $mimeType = $imageInfo['mime'];
    $thumb = imagecreatetruecolor($newWidth, $newHeight);
    $source = createImageAccordingType($originalFilePath, $mimeType);
    if (!$source)
        return false;
    
    imagecopyresized($thumb, $source, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

    $parts = explode('.', $originalFilename);
    $filename = $parts[0];
    $newFilename = sprintf('%s_%s.%s', 
        $parts[0],
        $version,
        $parts[1]
    );
    
    saveImageToDisk($thumb, $newFilename, $mimeType);
    imagedestroy($thumb);
}

?>