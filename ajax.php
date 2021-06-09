<?php
include "connection/connectadmin.php";
if(isset($_POST['action']) && $_POST['action'] == 'addintocart'){
	$client_id = $_POST['cilent_id'];
	$arrayName = array();
	if (isset($_COOKIE['cart'])) {
		$cart = json_decode($_COOKIE['cart']);
		$arrayName = $cart;
		if (!in_array($client_id, $arrayName)) {
			array_push($arrayName,$client_id);
		}
	}
	else{
		$arrayName[] = $client_id;
	}
	setcookie('cart', json_encode($arrayName), time() + (3600), "/");
	echo sizeof($arrayName);
}
if(isset($_POST['action']) && $_POST['action'] == 'removingchart'){
	$client_id = $_POST['cilent_id'];
	$arrayName = array();
	$result = array();
	$cart = json_decode($_COOKIE['cart']);
	$totalPrice = 0;
	for ($i=0; $i < sizeof($cart); $i++) { 
		if($cart[$i] != $client_id){
			$arrayName[] = $cart[$i];
			$sql = mysqli_query($connection, "SELECT * FROM client WHERE client_id = ".$cart[$i]) or die(mysqli_error($connection));
            while ($row = mysqli_fetch_assoc($sql)) {
              $totalPrice += $row['price'];
          	}
		}
	}
	setcookie('cart', json_encode($arrayName), time() + (3600), "/");
	$result["total_product"] = sizeof($arrayName);
	$result["total_price"] = $totalPrice;
	print json_encode($result);
}
?>