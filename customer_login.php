<?php
session_start();
$page = "Login";
include "connection/connectforUser.php";
if(isset($_SESSION['customer_id'])){
   header("Location: customer-account");
}
if(isset($_POST["login"])){
  $email = $_POST['email'];
  $password = $_POST['password'];
  $sql = mysqli_query($connection, "SELECT * FROM customer WHERE customer_email = '$email' AND customer_pwd  = '$password'") or die(mysqli_error($connection));
  $num = mysqli_num_rows( $sql);
  if ($num > 0) {
    $_SESSION["customer_email"] = $username;
    $_SESSION['CREATED'] = time();
    while ($row = mysqli_fetch_assoc($sql)) {
      $_SESSION['customer_name'] = $row['customer_name'];
      $_SESSION['customer_id'] = $row['customer_id'];
    }
    if($_COOKIE['locationafter']){
      $location  = $_COOKIE['locationafter'];
      setcookie('locationafter', '', time() - (3600), "/"); // deleting cookies before redirecting into checkout
      header($location); 
    }
    else{
      header("Location: customer-account");
    }
  }
  else{
    $error = "Invalid login. Please enter correct email or password.";
  }
}
if (isset($_POST['register'])) {
  $firstname = $_POST['firstname'];
  $lastname = $_POST['lastname'];
  $customer_name = $firstname.' '.$lastname;
  $customer_email = $_POST['customer_email'];
  $customer_pwd = $_POST['customer_pwd'];
  if(checkforemail($customer_email)){
    $insert = mysqli_query($connection, "INSERT INTO customer (customer_name, customer_email, customer_pwd) VALUES ('$customer_name','$customer_email', '$customer_pwd')") or die(mysqli_error($connection));
    $customer_id = mysqli_insert_id($connection);
    if($insert){
      $_SESSION["customer_email"] = $customer_email;
      $_SESSION['CREATED'] = time();
      $_SESSION['customer_name'] = $customer_name;
      $_SESSION['customer_id'] = $customer_id;
      if(isset($_COOKIE['locationafter'])){
        $location  = $_COOKIE['locationafter'];
        setcookie('locationafter', '', time() - (3600), "/"); // deleting cookies before redirecting into checkout
        header($location); 
      }
      else{
        header("Location: customer-account");
      }
    }
    else{
      $error = "Error while entering into the database.";
    }
  }
  else{
    $error = "Error: email is used by another user. Please try again with different email or request for changing password";
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
              <?php if(isset($error)): ?>
              <div class="col-12 text-center ">
                <p class="errormsg messagetodisplay">
                  <?php print $error; ?>  
                </p>
                
              </div>
              <?php endif; ?>
              <div class="col-md-6">
                <h4>Login</h4>
                <hr/>
                <form action="" method="POST">
                  <div class="mb-3">
                    <label for="exampleInputEmail" class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" id="exampleInputEmail" placeholder="Enter your email" required="">
                  </div>
                  <div class="mb-3">
                    <label for="exampleInputPassword" class="form-label">Password</label>
                    <input type="password" class="form-control" name="password" id="exampleInputPassword" aria-describedby="emailHelp" placeholder="Enter your password" required="">
                  </div>
                  <button type="submit" name="login" class="btn btn-primary mb-3">Login</button>
                </form>
              </div>
              <div class="col-md-6 border-left">
                <h4>Register</h4>
                <hr/>
                <form action="" method="POST">
                  <div class="mb-3">
                  <label for="exampleInputName" class="form-label">Full Name</label>
                  <div class="row">
                    <div class="col-md-6"><input type="text" name="firstname" class="form-control" id="exampleInputName" placeholder="First Name" required=""></div>
                    <div class="col-md-6"><input type="text" name="lastname" class="form-control" id="exampleInputName" placeholder="Last Name" required=""></div>
                  </div>
                  
                </div>
                <div class="mb-3">
                  <label for="exampleInputAddress" class="form-label">Address</label>
                  <input type="text" name="customer_address" class="form-control" id="exampleInputAddress" placeholder="Enter your address ">
                </div>
                <div class="mb-3">
                  <div class="row">
                    <div class="col-md-6">
                      <label for="exampleInputAddress" class="form-label">Delivery Area</label>
                      <select class="form-control" name="address_code" required="">
                        <option disabled="" selected="">---------</option>
                        <?php  
                        $sql = mysqli_query($connection, "SELECT * FROM area") or die(mysqli_error($connection));
                        while ($row = mysqli_fetch_assoc($sql)) {
                          print '<option value="'.$row['area_id'].'">'.$row['address'].'</option>';
                        }
                        ?>
                      </select>
                    </div>
                    <div class="col-md-6">
                      <label for="exampleInputGender" class="form-label">Gender</label>
                      <select class="form-control" name="customer_gender" id="exampleInputGender">
                        <option disabled="" selected="">---------</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                      </select>
                    </div>
                  </div>
                </div>
                <div class="mb-3">
                  <label for="exampleInputPhone" class="form-label">Phone</label>
                  <input type="text" class="form-control" name="customer_phone" id="exampleInputPhone" required="" placeholder="Enter your mobile or phone number">
                </div>
                <div class="mb-3">
                  <label for="exampleInputEmail1" class="form-label">Email address</label>
                  <input type="email" class="form-control" name="customer_email" id="exampleInputEmail1" aria-describedby="emailHelp" required="" placeholder="Enter your email address">
                </div>
                <div class="mb-3">
                  <label for="exampleInputPassword" class="form-label">Password</label>
                  <input type="password" name="customer_pwd" class="form-control" id="exampleInputPassword">
                </div>
                <button type="submit" name="register" class="btn btn-primary mb-3 mx-auto">Register</button>
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