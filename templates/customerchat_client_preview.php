<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">

    <title>CleverMessenger &middot; Test Client Website</title>
    <link class="clevermessenger-css" rel="stylesheet" href="../css/shared/client.min.css">
    <style>
        html,
        body {
            width: 100%;
            height: 100%;
        }

        body {
            margin: 0;
        }

        .widget-headline.widget-description{
            white-space: pre-line;
        }



        label.image-dropzone {
            position: relative;
            margin-bottom: 10px;
            -webkit-border-radius: 5px;
            -moz-border-radius: 5px;
            border-radius: 5px;
            padding: 80px 0;
            text-align: center
        }

        label.image-dropzone.hovered {
            border-color: rgba(0, 132, 255, 0.5)
        }

        label.image-dropzone.dragged-on {
            border-color: #0084FF;
            background: #F8F8F8
        }

        label.image-dropzone input[type='file'] {
            display: none
        }

        label.image-dropzone div.image-dropzone-image {
            position: absolute;
            top: 0;
            left: 0;
            margin: 10px;
            -webkit-border-radius: 5px;
            -moz-border-radius: 5px;
            border-radius: 5px;
            width: calc(100% - 20px);
            height: calc(100% - 20px);
            background-repeat: no-repeat;
            background-position: center center;
            background-size: contain;
            z-index: -1
        }

        label.image-dropzone div.image-dropzone-message {
            padding: 5px;
            background: rgba(255, 255, 255, 0.6)
        }

    </style>
</head>

<body>
<script>

    var BASE_URL = 'https://<?php echo $_SERVER["SERVER_NAME"] ?>/';
    var BASE_UPLOAD_URL = 'https://<?php echo $_SERVER["SERVER_NAME"] ?>/includes/customerchat';
</script>

<script class="clevermessenger-js" src="../js/customerchat/client.min.js"></script>
<script>


</script>
</body>
</html>
