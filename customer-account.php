<?php
session_start();
$page = "My account";
include "connection/connectforUser.php";
if(!isset($_SESSION['customer_id'])){
   header("Location: customer-login");
}
if(isset($_POST["update_password"])){
  $customer_id = $_POST['customer_id'];
  $current_password = $_POST['current_password'];
  $new_password = $_POST['new_password'];
  $renew_password = $_POST['renew_password'];
  $sql = mysqli_query($connection, "SELECT * FROM customer WHERE customer_id = $customer_id AND customer_pwd  = '$current_password'") or die(mysqli_error($connection));
  $num = mysqli_num_rows( $sql);
  if ($num > 0) {
    if ($new_password == $renew_password) {
      $update = mysqli_query($connection, "UPDATE customer SET customer_pwd = '$new_password' WHERE customer_id = $customer_id")  or die(mysqli_error($connection));
      $sucess = "Your password has been changed";
    }
    else{
      $error = "Your new passwords does not match";
      $errorform = "changepwdmodel";
    }
  }
  else{
    $error = "Your old password is not correct.";
    $errorform = "changepwdmodel";
  }
}
if (isset($_POST['update'])) {
  $customer_id = $_POST['customer_id'];
  $customer_address = $_POST['customer_address'];
  $address_code = $_POST['address_code'];
  $update = mysqli_query($connection, "UPDATE customer SET customer_address = '$customer_address', address_code = $address_code WHERE customer_id = $customer_id")  or die(mysqli_error($connection));
  if($update){
    if(isset($_COOKIE['locationafter'])){
      $location  = $_COOKIE['locationafter'];
      setcookie('locationafter', '', time() - (3600), "/"); // deleting cookies before redirecting into checkout
      header($location); 
    }
    else{
      $sucess = "Your password has been changed";
    }
  }
  else{
    $error = "Error while upating data into the database.";
    $errorform = "changeaddressmodel";
  }
  
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
        <div class="col-md-12 mx-auto">
          <div class="card-body">
            <div class="row">
              <?php if(isset($sucess)): ?>
              <div class="col-12 text-center ">
                <p class="messagetodisplay">
                  <?php print $sucess; ?>  
                </p>
                
              </div>
              <?php endif; ?>
              <div class="col-md-6">
                <h4>About You</h4>
                <hr/>
                <?php
                $customer_id = $_SESSION['customer_id'];
                $sql_data = mysqli_query($connection, "SELECT * FROM customer WHERE customer_id = $customer_id") or die(mysqli_error($connection));
                $dataforcustomer = mysqli_fetch_assoc($sql_data);
                 ?>
                <p><strong>Name: </strong><?php print $dataforcustomer['customer_name']; ?></p>
                <p><strong>Email: </strong><?php print $dataforcustomer['customer_email']; ?></p>
                <p><strong>Phone: </strong><?php print $dataforcustomer['customer_phone']; ?></p>
                <p><strong>Address: </strong><?php print $dataforcustomer['customer_address']; ?></p>
                <p><strong>Gender: </strong><?php print $dataforcustomer['customer_gender']; ?></p>
                <button class="btn btn-primary" data-toggle="modal" data-target="#changeaddressmodel">Change Detail</button> <button class="btn btn-warning" data-toggle="modal" data-target="#changepwdmodel">Change Password</button> <a href="logout"><button class="btn btn-danger">Logout</button></a>
              </div>
              <div class="col-md-6 border-left">
                <h4>Delivery For today</h4>
                <hr/>
                <?php
                $date = date('Y-m-d');
                $sqlorder = mysqli_query($connection, "SELECT * FROM order_delivery WHERE order_id IN (SELECT order_id FROM orders WHERE customer_id = $customer_id AND status = 1) AND enter_date = '$date'") or die(mysqli_error($connection));
                $numorder = mysqli_num_rows( $sqlorder);
                if($numorder > 0){
                  print '<p>Your newspaper has been delivered to your address.</p>';
                }
                else{
                  print '<p>Your newspaper has not been delivered to your address. Please wait for while or click here to <a href="notavaliable">report</a>.</p>';
                }
                ?>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="modal fade" id="changeaddressmodel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Change your detail</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="row">
              <?php if(isset($error)): ?>
              <div class="col-12 text-center ">
                <p class="errormsg messagetodisplay">
                  <?php print $error; ?>  
                </p>
                
              </div>
              <?php endif; ?>
              <div class="col-md-12">
                <form action="" method="POST">
                  <div class="mb-3">
                    <input type="hidden" name="customer_id" value="<?php print $customer_id; ?>">
                    <label for="exampleInputaddress" class="form-label">Address</label>
                    <input type="text" name="customer_address" class="form-control text-dark" id="exampleInputaddress" placeholder="Enter your full address" required="" value="<?php print $dataforcustomer['customer_address']; ?>">
                  </div>
                  <div class="mb-3">
                    <label for="exampleSelectAreacode" class="form-label">Area Code</label>
                    <select class="form-control text-dark" id="exampleSelectAreacode" name="address_code">
                      <option disabled="" selected="">Chose one</option>
                      <?php
                      $sql = mysqli_query($connection,"SELECT * FROM area") or die(mysqli_error($connection));
                      while($row = mysqli_fetch_assoc($sql)){
                        print '<option value="'.$row['area_id'].'"';
                        if($row['area_id'] == $dataforcustomer['address_code']){ print ' selected '; }
                        print '>'.$row['area_name'].'</option>';
                      } 
                      ?>
                    </select>
                  </div>
                  <button type="submit" name="update" class="btn btn-primary mb-3">Update</button>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="modal fade" id="changepwdmodel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Change your detail</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="row">
              <?php if(isset($error)): ?>
              <div class="col-12 text-center ">
                <p class="errormsg messagetodisplay">
                  <?php print $error; ?>  
                </p>
                
              </div>
              <?php endif; ?>
              <div class="col-md-12">
                <form action="" method="POST">
                  <div class="mb-3">
                    <input type="hidden" name="customer_id" value="<?php print $customer_id; ?>">
                    <label for="exampleInputpwd" class="form-label">Your Old Password</label>
                    <input type="password" name="current_password" class="form-control text-dark" id="exampleInputpwd">
                  </div>
                  <div class="mb-3">
                    <label for="exampleInputnew_password" class="form-label">New Password</label>
                    <input type="password" name="new_password" class="form-control text-dark" id="exampleInputnew_password">
                  </div>
                  <div class="mb-3">
                    <label for="exampleInputrenew_password" class="form-label">Re-type New Password</label>
                    <input type="password" name="renew_password" class="form-control text-dark" id="exampleInputrenew_password">
                  </div>
                  <button type="submit" name="update_password" class="btn btn-primary mb-3">Update</button>
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
    <script type="text/javascript">
      <?php if (isset($error)) {
        ?>
          $('#<?php print $errorform; ?>').modal('show');
        <?php  
      } ?>
    </script>
    </body>
</html>