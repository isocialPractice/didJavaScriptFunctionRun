<!doctype html>
<html>
<head>
 <meta charset="utf-8">
 <title>Did JavaScript Function Run</title>
 <link rel="icon" href="favicon.png">
 
 <style> 
  .error {
   color: #FF0000;
  }
  body {
   margin-left: 50px;
  }
 </style>

<!-- STEP TWO -->


</head>

<body>

<h1>Did JavaScript Function Run Example:</h1><hr>

<?php
 ini_set("include_path", '../php:' . ini_get("include_path") );

/***********************************************************************************************
           SAMPLE PHP
           Sample extracted from - https://www.w3schools.com/php/php_form_complete.asp
************************************************************************************************/

// define variables and set to empty values
$nameErr = $emailErr = $genderErr = $websiteErr = "";
$name = $email = $gender = $comment = $website = "";
$emailMessage = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (empty($_POST["name"])) {
    $nameErr = "Name is required";
    $name = "name not submitted";
  } else {
    $name = test_input($_POST["name"]);
    // check if name only contains letters and whitespace
    if (!preg_match("/^[a-zA-Z-' ]*$/",$name)) {
      $nameErr = "Only letters and white space allowed";
    }
  }
  
  if (empty($_POST["email"])) {
    $emailErr = "Email is required";
    $email = "name@example.us";
  } else {
    $email = test_input($_POST["email"]);
    // check if e-mail address is well-formed
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $emailErr = "Invalid email format";
    }
  }
    
  if (empty($_POST["website"])) {
    $website = "";
    $website = "website not submitted";
  } else {
    $website = test_input($_POST["website"]);
    // check if URL address syntax is valid (this regular expression also allows dashes in the URL)
    if (!preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i",$website)) {
      $websiteErr = "Invalid URL";
    }
  }

  if (empty($_POST["comment"])) {
    $comment = "";
    $comment = "comment not submitted";
  } else {
    $comment = test_input($_POST["comment"]);
  }

  if (empty($_POST["gender"])) {
    $genderErr = "Gender is required";
    $gender = "gender not submitted";
  } else {
    $gender = test_input($_POST["gender"]);
  }
}

 function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;        
 }
 
?>


<!-- STEP THREE -->


<p><span class="error">* required field</span></p>
<form id="demo-form" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>"> 
<!-- STEP FOUR -->
 
 

  <label for="name">Name: </label>
  <input type="text" name="name" value="<?php echo $name;?>">
  <span class="error">* <?php echo $nameErr;?></span>
  
  <br><br>
  
  <label for="gender">Gender:</label>
  <input type="radio" name="gender" <?php if (isset($gender) && $gender=="female") echo "checked";?> value="female">Female
  <input type="radio" name="gender" <?php if (isset($gender) && $gender=="male") echo "checked";?> value="male">Male
  <input type="radio" name="gender" <?php if (isset($gender) && $gender=="other") echo "checked";?> value="other">Other  
  <span class="error">* <?php echo $genderErr;?></span>  
  
  <br><br>
  
  <label for="email">E-mail: </label>
  <input type="text" name="email" value="<?php echo $email;?>">
  <span class="error">* <?php echo $emailErr;?></span>
  
  <br><br>
  
  <label for="website">Website:</label> 
  <input type="text" name="website" value="<?php echo $website;?>">
  <span class="error"><?php echo $websiteErr;?></span>
  
  <br><br>
  
  <label for="comment">Comment:</label> <br>
  <textarea name="comment" rows="5" cols="40"><?php echo $comment;?></textarea>  

  <br><br>
  
  <input type="submit" name="submit" value="Submit">   
 <br>               
</form>
<hr>

<!-- STEP FIVE -->



<!-- STEP SIX -->


<?php
/**********************************************************************************
                         PHP THAT SUCCESSFULLY HANDLES FORM
                         
Sample extracted from - https://www.w3schools.com/php/php_form_complete.asp
***********************************************************************************/


$formSub = '
  <div class="page-headline text-left">
   <h1>Form Submitted</h1>
   <p class="lead">Thanks for the submission! I will try and get back with you as soon as possible.</p>
  </div>';

  if ($comment == true and $email == true and $emailErr == "") {
   $emailMessage =  "Your Input: \n
     Name - $name  \n
     Gender - $gender \n   
     Email - $email \n
     Website - $website \n 
     \n\n
     Feedback - $comment";
   
   // Send response to server.
   mail("itWorked@practicing.xyz", "Web Comment", $emailMessage, $email);
   // Alter with successful submit.
   echo $formSub;
  } elseif ($comment == true or $email == true or $emailErr == true) {
   // Alter with unsuccessful submit.
   echo '
    <div class="page-headline text-left">
     <h1>Form NOT Submitted</h1>
     <p class="lead">Refresh page and fill in required fields and try again.</p>
    </div>';
  } 
  
/*  

   // FOR EXAMPLE PURPOSES ONLY
   // ************************* 
 if ($comment == true and $email == true and $emailErr == "") {
  // show form server handling of form submitted when JavaScript did not run in browser
  mail("itWorked@practicing.xyz","Web Comment", "no",$email);
 } 
 echo "";    
 
  
*/

?>
 


 
</body>
</html>