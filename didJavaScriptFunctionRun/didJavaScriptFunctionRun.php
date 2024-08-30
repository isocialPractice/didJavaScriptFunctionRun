<?php
 // didJavaScriptFunctionRun
 // PHP portion of repo didJavaScriptFunctionRun
 
 $serverResponse = $_SERVER["REQUEST_METHOD"]; 
 $didJavaScriptFunctionRun_file = "no JS"; 
 
 // include check user agent.
 include("scripts/check_header_did_javascript_function_run.php");
 
 if ($user_agent_did_javascript_function_run == 0) { 
  // Set with posted data or empty value.
  if (isset($_POST["didJavaScriptFunctionRunID"])) {
   $didJavaScriptFunctionRunID = $_POST["didJavaScriptFunctionRunID"];  
  } else {
   $didJavaScriptFunctionRunID = ""; 
  }

  // If calling from root in page where this is included use relative path.
  $where_is_the_file_checked = "tmp/"; // IMPORTANT - relative path

  if ($serverResponse == "POST") {
   if (empty($_POST[$name_of_required_form_field])) {
     $name_form_input = "";                   // empty
    if (!empty($_POST["didJavaScriptFunctionRunID"])) {
      $didJavaScriptFunctionRunID =           // value should be random though
       $_POST["didJavaScriptFunctionRunID"];   // so check if random

      // NOTE - this condition has to be repeated.
      if ( // When the submit button is clicked the AJAX call creates random elements.
           // Here they are checked.
       file_exists($where_is_the_file_checked . $didJavaScriptFunctionRunID . "/file.txt")
      ) {  // If there is a file, then:      
        $didJavaScriptFunctionRun_file =      // get the contents
         file_get_contents($where_is_the_file_checked . $didJavaScriptFunctionRunID . "/file.txt");

        $didJavaScriptFunctionRun_file =      // remove appended empty data
         substr($didJavaScriptFunctionRun_file, 0, count($didJavaScriptFunctionRun_file)-2);           
        $check_action = 1;                    // turn on delete tmp file switch
      }
     } 
    } else {
     $didJavaScriptFunctionRunID =            // check post
      $_POST["didJavaScriptFunctionRunID"];   // should be random  
      // NOTE - this condition has to be repeated.     
      if ( // When the submit button is clicked the AJAX call creates random elements.
           // Here they are checked.
       file_exists($where_is_the_file_checked . $didJavaScriptFunctionRunID . "/file.txt")
      ) {  // If there is a file, then:      
        $didJavaScriptFunctionRun_file = // get the contents
         file_get_contents($where_is_the_file_checked . $didJavaScriptFunctionRunID . "/file.txt");

        $didJavaScriptFunctionRun_file = // remove appended empty data
         substr($didJavaScriptFunctionRun_file, 0, count($didJavaScriptFunctionRun_file)-2);
      }
     if ( // Random characters posted in hidden element equal random file.
      $didJavaScriptFunctionRunID == $didJavaScriptFunctionRun_file
      ) { // Define form fields with post data.
       $name_form_input = $_POST[$name_of_required_form_field];      
       $check_action = 1;     // tunrn on delete tmp file switch
     } else {
       $check_action = 0;     // turn off delete tmp file switch
       $name_form_input = ""; // empty form field
     }
    }   
   } 
  }
 
  ?>