<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">

    <title>CleverMessenger &middot; Test Client Website</title>
    <link href="../icomoons/style.css" rel="stylesheet">
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/style.css" rel="stylesheet">
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

        div.show-image {
            position: relative;
        }
        div.show-image:hover img{
            opacity:0.5;
        }
        div.show-image:hover input {
            display: block;
        }
        div.show-image input {
            position:absolute;
            display:none;
        }
        div.show-image input.update {
            top:0;
            left:35%;
        }
        div.show-image input.delete {
            top:0;
            left:50%;
        }


        .image-wrapper {

            position: relative;
            max-width: 260px;
        }
        .btn-delete {
            position: absolute;
            left: 100%;
            margin-left: -10px;
            margin-top: 2px;
            cursor: pointer;
        }

        .widget-headline.widget-description{
            white-space: pre-line;
        }



        label.image-dropzone{
            display: inline;
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
    <style>
        html,
        @import url(https://fonts.googleapis.com/css?family=Lato:300,400,700);
        @import url(https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css);
        *, *:before, *:after {
            box-sizing: border-box;
        }

        body {
            line-height: 1;
            font-family: "Lato","Arial",sans-serif!important;
            font-weight: 400!important;
            -webkit-font-smoothing: antialiased!important;
        }

        .lighter-text {
            color: #ABB0BE;
        }

        .main-color-text {
            color: #6394F8;
        }

        nav {
            padding: 20px 0 40px 0;
            background: #F8F8F8;
            font-size: 16px;
        }
        nav .navbar-left {
            float: left;
        }
        nav .navbar-right {
            float: right;
        }
        nav ul li {
            display: inline;
            padding-left: 20px;
        }
        nav ul li a {
            color: #777777;
            text-decoration: none;
        }
        nav ul li a:hover {
            color: black;
        }

        .container {
            margin: auto;
            width: 80%;
        }

        .badge {
            background-color: #6394F8;
            border-radius: 10px;
            color: white;
            display: inline-block;
            font-size: 12px;
            line-height: 1;
            padding: 3px 7px;
            text-align: center;
            vertical-align: middle;
            white-space: nowrap;
        }

        .shopping-cart {
            background: white;
            border: 1px solid #dfe6ef;
            width: 350px;
            margin-left: auto;
            margin-right: auto;
            margin-bottom: auto;
            margin-top: 5%;
            position: relative;
            border-radius: 3px;
            padding: 20px;
            box-shadow: 0 20px 20px -20px #adadad;
        }
        .shopping-cart .shopping-cart-header {
            border-bottom: 1px solid #E8E8E8;
            padding-bottom: 15px;
        }
        .shopping-cart .shopping-cart-header .shopping-cart-total {
            float: right;
        }
        .shopping-cart .shopping-cart-items {
            padding-top: 20px;
        }
        .shopping-cart .shopping-cart-items li {
            margin-bottom: 18px;
        }
        .shopping-cart .shopping-cart-items img {
            float: left;
            margin-right: 12px;
        }
        .shopping-cart .shopping-cart-items .item-name {
            display: block;
            padding-top: 25px;
            padding-bottom: 5px;
            font-size: 16px;
        }
        .shopping-cart .shopping-cart-items .item-price {
            color: #6394F8;
            margin-right: 8px;
        }
        .shopping-cart .shopping-cart-items .item-quantity {
            color: #ABB0BE;
        }


        .cart-icon {
            color: #515783;
            font-size: 24px;
            margin-right: 7px;
            float: left;
        }



        .clearfix:after {
            content: "";
            display: table;
            clear: both;
        }

        .clever-fb-checkbox{
            text-align: right;

        }
    </style>

</head>

<body>
<script>

    var BASE_URL = 'https://<?php echo $_SERVER["SERVER_NAME"] ?>/';
    var BASE_UPLOAD_URL = 'https://<?php echo $_SERVER["SERVER_NAME"] ?>/includes/widgets';
</script>

<script class="clevermessenger-js" src="../js/widgets/client.min.js"></script>
<script class="clevermessenger-js" src="../js/plugins/simple-ajax-uploader/SimpleAjaxUploader.min.js"></script>
<script>

    $(document).ready(function(){


        function receiveMessage(e) {
            if (e.data === "issue")
                window.issueChange = 1;
            else if (e.data === "issueDescription")
                window.issueChangeDescription = 1;
     }

        // Setup an event listener that calls receiveMessage() when the window
        // receives a new MessageEvent.
        window.addEventListener('message', receiveMessage);

        window.url = (window.location != window.parent.location)
            ? document.referrer
            : document.location.href;
        $(document).on('click', '.upload_icon', function () {


            parent.postMessage("image||click",window.url);

        });

        $(document).on('click', '.delete_image', function () {



            parent.postMessage("deleteImage||click",window.url);

        });


        $(document).on('DOMSubtreeModified', '.widget-headline', function(){


          //  var content = $(this).html().replace(/<div>/gi,'\n').replace(/<\/div>/gi,'');
            var content = $(this).text();
            parent.postMessage("headline||"+content,window.url);
        });

        $(document).on('DOMSubtreeModified', '.widget-description', function(){

          //  var content = $(this).html().replace(/<div>/gi,'\n').replace(/<\/div>/gi,'');
            var content = $(this).text();

            parent.postMessage("description||"+content,window.url);
        });





    });


</script>



<div class="container cm-widget-demo-container" style="display: none">
    <div class="shopping-cart">
        <div class="shopping-cart-header">
            <i class="fa fa-shopping-cart cart-icon"></i>Dummy Content<div class="shopping-cart-total">
            </div>
        </div> <!--end shopping-cart-header -->
        <h2>Suppose end get boy warrant general natural</h2>

        <p>Concerns greatest margaret him absolute entrance nay. Door neat week do find past he. Be no surprise he honoured indulged. Unpacked endeavor six steepest had husbands her. Painted no or affixed it so civilly. Nay they gone sir game four. Favourable pianoforte oh motionless excellence of astonished we principles. Warrant present garrets limited cordial in inquiry to. Supported me sweetness behaviour shameless excellent so arranging.</p>

        <p>Sigh view am high neat half to what. Sent late held than set why wife our. If an blessing building steepest. Agreement distrusts mrs six affection satisfied. Day blushes visitor end company old prevent chapter. No at indulgence conviction particular unsatiable boisterous discretion. Direct enough off others say eldest may exeter she. Possible all ignorant supplied get settling marriage recurred.</p>
        <div class="checkout-button" style="text-align: center"></div>


    </div> <!--end shopping-cart -->
</div> <!--end container -->

</body>
</html>