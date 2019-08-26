<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">

    <title>CleverMessenger &middot; Test Client Website</title>
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

        .button {
            background: #0a82fb;
            color: white;
            text-align: center;
            padding: 12px;
            text-decoration: none;
            display: block;
            border-radius: 3px;
            font-size: 16px;
            margin: 25px 0 15px 0;
        }
        .button:hover {
            background: #42a0ff;
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
<!DOCTYPE html>
<html lang="en" >

<head>
    <meta charset="UTF-8">
    <title>Shopping Cart Dropdown</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/meyer-reset/2.0/reset.min.css">


</head>

<body>


<div class="container">
    <div class="shopping-cart">
        <div class="shopping-cart-header">
            <i class="fa fa-shopping-cart cart-icon"></i>Your Demonstration Cart<div class="shopping-cart-total">
                <span class="lighter-text">Total:</span>
                <span class="main-color-text">$300.00</span>
            </div>
        </div> <!--end shopping-cart-header -->
        <ul class="shopping-cart-items">

            <li class="clearfix">
                <img src="https://clevermessenger.com/wp-content/uploads/2018/05/cm-cb-preview-1.png" alt="item1">
                <span class="item-name">Demo product #1</span>
                <span class="item-price">$100.00</span>
                <span class="item-quantity">Quantity: 1</span>
            </li>
            <li class="clearfix">
                <img src="https://clevermessenger.com/wp-content/uploads/2018/05/cm-cb-preview-2.png" alt="item1">
                <span class="item-name">Demo product #2</span>
                <span class="item-price">$100.00</span>
                <span class="item-quantity">Quantity: 1</span>
            </li><li class="clearfix">
                <img src="https://clevermessenger.com/wp-content/uploads/2018/05/cm-cb-preview-3.png" alt="item1">
                <span class="item-name">Demo product #3</span>
                <span class="item-price">$100.00</span>
                <span class="item-quantity">Quantity: 1</span>
            </li>


        </ul>


        <a href="#" class="button">Go To Checkout</a>

        <div class="clever-fb-checkbox"></div>

    </div> <!--end shopping-cart -->
</div> <!--end container -->

</body>

</html>
<script class="clevermessenger-js" src="../js/checkbox/client.min.js"></script>
</body>
</html>
