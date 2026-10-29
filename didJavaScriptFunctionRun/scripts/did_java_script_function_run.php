<?php
 // did_java_script_function_run
 // Final check, storing 1 or 0 in the main variable.

 include_once(__DIR__ . "/clean_random_character_function.php");

 // Keep the posted random characters only if they are valid.
 if (!isset($didJavaScriptFunctionRunID)) {
  $didJavaScriptFunctionRunID = "";
 }
 $didJavaScriptFunctionRunID = clean_random_character_function($didJavaScriptFunctionRunID);

 // turn on variable
 $didJavaScriptFunctionRun = 0;
 if ($didJavaScriptFunctionRunID != "") {
  $random_folder_did_javascript_function_run = dirname(__DIR__) . "/tmp/" . $didJavaScriptFunctionRunID;
  if (is_file($random_folder_did_javascript_function_run . "/file.txt")) {   // Blocks where JavaScript did run.
   // Remove the random files created.
   @unlink($random_folder_did_javascript_function_run . "/file.txt");
   @rmdir($random_folder_did_javascript_function_run);
   $didJavaScriptFunctionRun = 1;
  }
 }

?>