<?php include("functions.php"); ?>

<!doctype html>
    <head>
        <meta charset="utf-8">
        <title></title>
        
        <link rel="stylesheet" href="css/normalize.min.css">
        <link rel="stylesheet" href="css/main.css">

        <script src="js/vendor/modernizr-2.8.3-respond-1.4.2.min.js"></script>
    </head>
    <body>
        <h1>Miani Upload - Come on. Give it a shot! ;) </h1>
        
        <?php if (isset($_SESSION['errors']) && sizeof($_SESSION['errors']) > 0): ?>
        <div class="errors">
            <ul>
                <?php foreach ($_SESSION['errors'] as $message) { ?>
                    <li><?php echo $message; ?></li>
                <?php } ?>
            </ul>            
        </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['uploadedFiles']) && sizeof($_SESSION['uploadedFiles']) > 0) { ?>            
        <div>
            <ul>
                <?php 
                $uploadedFiles = array_reverse($_SESSION['uploadedFiles']);
                foreach ($uploadedFiles as $file) { 
                    $parts = explode('.', $file);
                    $smallFilePath = sprintf('%s_%s.%s', $parts[0], 'small', $parts[1]);
                    $mediumFilePath = sprintf('%s_%s.%s', $parts[0], 'medium', $parts[1]);
                    $largeFilePath = sprintf('%s_%s.%s', $parts[0], 'large', $parts[1]);

                    $fileHash = urlencode(encrypt($file));
                    $smallFileHash = urlencode(encrypt($smallFilePath));
                    $mediumFileHash = urlencode(encrypt($mediumFilePath));
                    $largeFileHash = urlencode(encrypt($largeFilePath));                    
                    ?>
                    
                    <li>File upload on <?php echo humanDate($file); ?>
                        <ul>
                            <li><a href="show_image.php?file=<?php echo $fileHash ?>">Original</li>
                            <li><a href="show_image.php?file=<?php echo $smallFileHash ?>">Small</a></li>
                            <li><a href="show_image.php?file=<?php echo $mediumFileHash ?>">Medium</a></li>
                            <li><a href="show_image.php?file=<?php echo $largeFileHash ?>">Large</a></li>
                        </ul>
                    </li>
                <?php } ?>
            </ul>            
        </div>
        <?php } ?>
    </body>
</html>
