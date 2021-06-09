<?php include 'connection/connectforUser.php';
$page = "About Us";
if (isset($_POST['submit'])) {
  if (!isset($_POST['firstname']) || $_POST['firstname'] == '' || !isset($_POST['lastname']) || $_POST['lastname'] == '' || !isset($_POST['email']) || $_POST['email'] == ''){
    $error = "Please enter all required fields";
  }
  else{
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];
    $insert = mysqli_query($connection, "INSERT INTO contactus (firstname, lastname, email, subject, message) VALUES ('$firstname', '$lastname', '$email', '$subject', '$message')") or die(mysqli_error($connection));
    if($insert){
      $sucessfull = "Thank you for your response.<br/> You will reply with your issues with 7 days.";
    }
    else{
      $error = "Error while entering into the database.";
    }
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
    <div class="row">
       <div class="col-md-10 mx-auto">
         <h1 class="text-center">
           About us
           <hr/>
         </h1>
          <p>Who are we?</p>
          <p>We own the small scale business of Delivery of Newspaper to our cutomer. This business had been opened 2 years ago and since that time we used to give our 100% to satisfy our customer by delivery their favorite Newspaper at their door steps in proper. So, that then can read their paper with the sip of coffee at the morning. If you wanna know more about us please provide your name and contact number in the form below than we will contact you as soon as possible. Thank You So MUch for your support.</p>
          <hr/>
          <h2 id="contactus" class="text-center">Contact Us</h2>
          <hr/>
          <form method="post" action="">
            <div class="row">
              <div class="col-md-10 mx-auto">
                <?php if(isset($error)){ 
                  print "<p class='errormsg messagetodisplay'>".$error."</p><hr/>"; 
                }
                elseif (isset($sucessfull)) {
                  print "<p class='sucessfulmsg messagetodisplay'>".$sucessfull."</p><hr/>"; 
                }
                ?>
                <div class="mb-3">
                  <label for="exampleInputName" class="form-label">Full Name</label>
                  <div class="row">
                    <div class="col-md-6"><input type="text" name="firstname" class="form-control" id="exampleInputName" placeholder="First Name" required=""></div>
                    <div class="col-md-6"><input type="text" name="lastname" class="form-control" id="exampleInputName" placeholder="Last Name" required=""></div>
                  </div>
                  
                </div>
                <div class="mb-3">
                  <label for="exampleInputEmail1" class="form-label">Email address</label>
                  <input type="email" class="form-control" name="email" id="exampleInputEmail1" aria-describedby="emailHelp" required="">
                  <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
                </div>
                <div class="mb-3">
                  <label for="exampleInputSubject" class="form-label">Subject</label>
                  <input type="text" name="subject" class="form-control" id="exampleInputSubject">
                </div>
                <div class="mb-3">
                  <label for="exampleInputMessage" class="form-label">Message</label>
                  <Textarea name="message" class="form-control" id="exampleInputMessage" rows="8"></Textarea>
                </div>
                <button type="submit" name="submit" class="btn btn-primary mb-3">Submit</button>
              </div>
            </div>
          </form>
       </div>
          
     </div>
  </div>
    
    <?php include 'footer.php'; ?>

<!--database content here-->

    <?php include 'script.php'; ?>
  </body>
</html>