<?php
session_start();
$page = "Checkout";
include "connection/connectforUser.php";
if(!isset($_COOKIE['cart'])){
	header("Location: order.php");
}
$cart = json_decode($_COOKIE['cart']);

if(!isset($_SESSION['customer_id'])){
	setcookie('locationafter', 'Location: checkout.php', time() + (3600), "/");
	header("Location: customer_login.php");
}
if(isset($_SESSION['account_created']) && $_SESSION['account_created'] ){
	setcookie('locationafter', 'Location: checkout.php', time() + (3600), "/");
	header("Location: customer-account");
}
if(isset($_POST['pay'])){
	$customer_id = $_POST['customer_id'];
	$orderitem = $_POST['client_id'];
	$insert = mysqli_query($connection, "INSERT INTO orders (customer_id, status) VALUES ($customer_id, 1)") or die(mysqli_error($connection));
    $order_id = mysqli_insert_id($connection);
	foreach ($orderitem as $key) {
		$insert = mysqli_query($connection, "INSERT INTO orderitems (order_id, client_id) VALUES ($order_id, $key)") or die(mysqli_error($connection));
	}
	setcookie('cart', '', time() - (3600), "/");
	header("Location: thankyou");
}
?>
<!doctype html>
<html lang="en">
  	<?php include 'head.php'; ?>
  	<body>
  		<?php include 'navbar.php'; ?>
  	<!--menu row -->
  	<div class="container">
    	
    	<div class="row mainbody">
	      	<div class="col-9 mx-auto">
	      		<div class="card-body">
		            <div class="row">
		            	<h3></h3>
		              	<?php if(isset($error)): ?>
		              	<div class="col-12 text-center ">
			                <p class="errormsg messagetodisplay">
			                  <?php print $error; ?>  
			                </p>
		              	</div>
		              	<?php endif; ?>
		              	<div class="col-md-12">
			                <h4>Your details</h4>
			                <hr/>
		                  	<div class="mb-3">
		                  		<?php
		                  		$sqldata = mysqli_query($connection, "SELECT * FROM customer WHERE customer_id = ".$_SESSION['customer_id']) or die(mysqli_error($connection));
		                  		$customerdata = mysqli_fetch_assoc($sqldata);
		                  		?>
		                  		<div class="row">
		                  			<div class="col-md-3">
		                  				<label><i class="fa fa-user"></i> Name: </label> <?php print $customerdata['customer_name']; ?>
		                  			</div>
		                  			<div class="col-md-4">
		                  				<label><i class="fa fa-envelope"></i> Email: </label> <?php print $customerdata['customer_email']; ?>
		                  			</div>
		                  			<div class="col-md-4">
		                  				<label><i class="fa fa-phone"></i> Phone: </label> <?php print $customerdata['customer_phone']; ?>
		                  			</div>
		                  		</div>
		                  		<div class="row">
		                  			<div class="col-md-12">
		                  				<label><i class="fa fa-map-pin"></i> Address: </label> <?php print $customerdata['customer_address']; ?>
		                  			</div>
		                  		</div>
			                  	
		                  	</div>
		                  	<a href="customer-account"><button class="btn btn-danger mb-3">Change</button></a>
		                  	<hr/>
		              	</div>
		              	<div class="col-md-12">
			                <h4>Order Details</h4>
			                <hr/>
			                <form action="" method="POST">
			                	<?php 
			                	$cart = json_decode($_COOKIE['cart']);
			                	$totalPrice = 0;
			                	$numberofproduct = sizeof($cart);
			                	?>
			                	<input type="hidden" name="customer_id" value="<?php print $_SESSION['customer_id'] ?>">
			                  	<div class="mb-3">
				                  	<label class="form-label">Number of product</label>: <?php print $numberofCartProduct; ?>
				                  	<div class="row">
					                    <?php 
					                	
					                  	foreach ($cart as $cartItem) {
					                    	$sql = mysqli_query($connection, "SELECT * FROM client WHERE client_id = ".$cartItem) or die(mysqli_error($connection));
					                    	while ($row =  mysqli_fetch_assoc($sql)) {
					                      		$totalPrice += $row['price'];
					                      		print '<input name="client_id[]" value="'.$row['client_id'].'" type="hidden"/>';
					                      		print 	'<div class="col-md-6">
					                      					<div class="card p-3">
					                      						<div class="row ">
											                        <div class="col-4 mb-4">
											                          <img style="width: 100%" src="images/'.$row['logo'].'" alt="'.$row['client_name'].'">
											                        </div>
											                        <div class="col-8 mb-4">
											                          <h4>'.$row['client_name'].'</h4>
											                           <p>Price: $'. $row['price'].'</p>
											                        </div>
											                    </div>
															</div>
														</div>';
					                      	}
					                    }
					                	?>
				                  	</div>
			                	</div>
				                <button type="submit" name="pay" class="btn btn-primary mb-3 mx-auto">Pay</button>
			                </form>
		              </div>
		            </div>
		          </div>
	      	</div>
    	</div>
  	</div>
   	<?php include 'footer.php'; ?>

	<!--database content here-->

    <?php include 'script.php'; ?>
   	</body>
</html>