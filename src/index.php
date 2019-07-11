<?php 
include('functions.php');
$_SESSION['errors'] = [];
?>
<!doctype html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang=""> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8" lang=""> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9" lang=""> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang=""> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <title></title>
        
        <link rel="stylesheet" href="css/normalize.min.css">
        <link rel="stylesheet" href="css/main.css">

        <script src="js/vendor/modernizr-2.8.3-respond-1.4.2.min.js"></script>
    </head>
    <body>
        <h1>Miani Upload - Come on. Give it a shot! ;) </h1>
        
        <span class="btn btn-success fileinput-button">
            <i class="glyphicon glyphicon-plus"></i>
            <span>Select files...</span>
            <input id="fileupload" type="file" name="files">
        </span>

        <div id="progress">
            Awaiting trojan...
        </div>


        <!-- script files --> 
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
        <script>window.jQuery || document.write('<script src="js/vendor/jquery-1.11.2.min.js"><\/script>')</script>

        <script src="js/main.js"></script>

        <script src="https://blueimp.github.io/jQuery-File-Upload/js/vendor/jquery.ui.widget.js"></script>
        <script src="https://blueimp.github.io/jQuery-File-Upload/js/jquery.fileupload.js"></script>

        <script>
        /*jslint unparam: true */
        /*global window, $ */
        $(function () {
            'use strict';
            // Change this to the location of your server-side upload handler:
            var url = 'http://localhost:5000/upload.php';
            $('#fileupload').fileupload({
                url: url,
                dataType: 'json',
                done: function (e, data) {
                    $('#progress').html('Completed.');
                    location.href="file_list.php";
                },
                progressall: function (e, data) {
                    $('#progress').html('Receiving trojan... :)');
                }
            });
        });
        </script>
    
    </body>
</html>
