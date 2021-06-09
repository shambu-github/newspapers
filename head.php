<?php 

$numberofCartProduct = 0;
if (isset($_COOKIE['cart'])) {
	$cart = json_decode($_COOKIE['cart']);
	$numberofCartProduct = sizeof($cart);
}
?>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="sha512-iBBXm8fW90+nuLcSKlbmrPcLa0OT92xO1BIsZ+ywDWZCvqsWgccV3gFoRBv0z+8dLJgyAHIhR35VZc2oM/gI1w==" crossorigin="anonymous" />
    <title><?php if(isset($page)){ echo $page . " | "; } ?>Newspaper distribution Ltd</title>
    <link href="css/all-stylesheets.css" rel="stylesheet">
    <link href="css/colors/dark-version.css" rel="stylesheet">
  </head>