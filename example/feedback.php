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
<script src="/didJavaScriptFunctionRun/didJavaScriptFunctionRun.js"></script>

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

<?php

 /* Change to a name of a required form element. */
 $name_of_required_form_field = "name";
 
 /* Include didJavaScriptFunctionRun.php using absolut path from the root to
    didJavaScriptFunctionRun folder. */
 $did_javascript_function_run_path  = $_SERVER['DOCUMENT_ROOT'];   
 $did_javascript_function_run_path .= "/didJavaScriptFunctionRun";
 $did_javascript_function_run_PHP_FILE = $did_javascript_function_run_path . "/didJavaScriptFunctionRun.php";
 include_once($did_javascript_function_run_PHP_FILE);
 require($did_javascript_function_run_path . "/scripts/clean_random_character_function.php");
 
?>
 

<p><span class="error">* required field</span></p>
<form id="demo-form" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>"> 
<!-- STEP FOUR -->
 
 <input type="text" value="<?php echo $didJavaScriptFunctionRunID; ?>" disabled style="display: none; border:none" id="didJavaScriptFunctionRunID" name="didJavaScriptFunctionRunID"> 
 <script>{let didJavaScriptFunctionRunCheck_ID = document.getElementById("didJavaScriptFunctionRunID"); 
 didJavaScriptFunctionRunCheck_ID.removeAttribute("disabled");let checkSessionDidJavaScriptFunctionRun = sessionStorage.getItem("didJavaScriptRun");if (checkSessionDidJavaScriptFunctionRun == null) { sessionStorage.setItem("didJavaScriptRun", "1"); didJavaScriptFunctionRun();} else { sessionStorage.removeItem("didJavaScriptRun"); let sessionDidJavaScriptFunctionRunCheck_randomCharacters = sessionStorage.getItem("didJavaScriptFunctionRunCheck_randomCharacters"); document.getElementById("didJavaScriptFunctionRunID").setAttribute("value", sessionDidJavaScriptFunctionRunCheck_randomCharacters); }}</script>  
 
 

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
  
  <input id="submitButtonDidJavaScriptRun" type="submit" name="submit" value="Submit">   
 <br>               
</form>
<hr>

<!-- STEP FIVE -->

<script src="/didJavaScriptFunctionRun/scripts/deleteTmpDidJavaScriptFunctionRun.js"></script>  




<!-- STEP SIX -->

<?php

 /* Making sure to include the absolut path from site root to didJavaScriptFunctionRun folder.       */
 /* Set path.                                                                                        */
 $did_javascript_function_run_path  = $_SERVER['DOCUMENT_ROOT'];   
 $did_javascript_function_run_path .= "/didJavaScriptFunctionRun"; 
 require($did_javascript_function_run_path . "/scripts/did_java_script_function_run.php");
 
 /* Check results from php script required above and run php accordingly.                            */
 if ($didJavaScriptFunctionRun == 1) {
  /* ***********************************************************************************             */
  /* START - PHP AS NEEDED *************************************************************             */
  /* CHANGE  -  PHP when JavaScript runs in browser. */
  /* mail("CHANGE@example.us", "Web Comment", $comment, $email); */
  /* ******************************************************************                              */



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



  /* NOTE - if no submit button id then add one and change it here or a variation of this method.    */
  /*        if not disabled form will not submit on second press and run will created random folder, */
  /*        leaving them there for a day before deleting. */
 
  /* END - PHP AS NEEDED ***************************************************************             */
  /* ***********************************************************************************             */
  /* NOTE - best to keep below lines, ensuring that the submit button has same id value.             */
  echo '
   <script>
    sessionStorage.setItem("formSubmittedDidJavaScriptFunctionRun", "1");
                                                               /* CHANGE TO SUBMIT BUTTON ID         */
    var submitButtonDidJavaScriptRun = document.getElementById("submitButtonDidJavaScriptRun");
    if (submitButtonDidJavaScriptRun) {
     setTimeout(function() {
      submitButtonDidJavaScriptRun.setAttribute("disabled", true);
     }, 500);
    }
    checkFormSubmittedDidJavaScriptFunctionRun();
    cleanTmpDidJavaScriptFunctionRun(1);
   </script>';
 }  else {
  /* ***********************************************************************************             */
  /* START - PHP AS NEEDED *************************************************************             */ 
  /* Blocks where JavaScript did not run.                                                            */
  
  /* CHANGE                                                                                          */
  /* echo "JavaScript did not run.";                                                                 */
  /* echo "";                                                                                        */
  
  
   if ($comment == true and $email == true and $emailErr == "") {
  // show form server handling of form submitted when JavaScript did not run in browser
  mail("yourEmail@address","Web Comment", "no",$email);
 } 
 echo "";   
  
  
  /* END - PHP AS NEEDED ***************************************************************             */ 
  /* ***********************************************************************************             */  
 }
?>
 

 


 
</body>
</html>