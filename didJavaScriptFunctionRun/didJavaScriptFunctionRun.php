<?php
 // didJavaScriptFunctionRun
 // PHP portion of repo didJavaScriptFunctionRun

 // Include cleaning function and check user agent.
 include_once(__DIR__ . "/scripts/clean_random_character_function.php");
 include_once(__DIR__ . "/scripts/check_header_did_javascript_function_run.php");
 check_header_did_javascript_function_run();

 // Every variable used by the template is defined on every path.
 $serverResponse = isset($_SERVER["REQUEST_METHOD"]) ? $_SERVER["REQUEST_METHOD"] : "GET";
 $didJavaScriptFunctionRun_file = "no JS";
 $didJavaScriptFunctionRunID    = "";
 $name_form_input               = "";
 $check_action                  = 0;
 if (!isset($name_of_required_form_field)) {
  $name_of_required_form_field = "";
 }

 if ($user_agent_did_javascript_function_run == 0) {
  // Set with posted data, kept only if it is valid random characters, so the
  // value is always safe to echo into the hidden input element.
  if (isset($_POST["didJavaScriptFunctionRunID"])) {
   $didJavaScriptFunctionRunID = clean_random_character_function($_POST["didJavaScriptFunctionRunID"]);
  }

  // Folder the random files are created in, found from this file's location.
  $where_is_the_file_checked = __DIR__ . "/tmp/";

  if ($serverResponse == "POST") {
   // When the submit button is clicked the AJAX call creates random elements.
   // Here they are checked.
   $random_file_found = false;
   if ($didJavaScriptFunctionRunID != "" &&
       is_file($where_is_the_file_checked . $didJavaScriptFunctionRunID . "/file.txt")) {
    $didJavaScriptFunctionRun_file =          // get the contents, without the line break
     trim((string) file_get_contents($where_is_the_file_checked . $didJavaScriptFunctionRunID . "/file.txt"));
    $random_file_found = true;
   }

   if (empty($_POST[$name_of_required_form_field])) {
    $name_form_input = "";                    // empty
    if ($random_file_found) {
     $check_action = 1;                       // turn on delete tmp file switch
    }
   } else {
    if ( // Random characters posted in hidden element equal random file.
     $random_file_found && $didJavaScriptFunctionRunID === $didJavaScriptFunctionRun_file
     ) { // Define form fields with post data.
      $name_form_input = $_POST[$name_of_required_form_field];
      $check_action = 1;                      // turn on delete tmp file switch
    } else {
      $check_action = 0;                      // turn off delete tmp file switch
      $name_form_input = "";                  // empty form field
    }
   }
  }
 }

?>
