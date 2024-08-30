<?php
 // did_java_script_function_run
 // Final check, storing 1 or 0 in the main variable.
 
 // Check if string was passed to input.
 if (strlen($didJavaScriptFunctionRunID) > 1) {
  // Clean
/**************** WHAT FUNCTION DOES *
  $didJavaScriptFunctionRunID = trim($didJavaScriptFunctionRunID); 
  $didJavaScriptFunctionRunID = htmlspecialchars($didJavaScriptFunctionRunID); 
  $didJavaScriptFunctionRunID = preg_replace('/[.\/=$\*]/', "", $didJavaScriptFunctionRunID);
***************** WHAT FUNCTION DOES */
  $didJavaScriptFunctionRunID = clean_random_character_function($didJavaScriptFunctionRunID);  
 } 
 
 // turn on variable
 if (file_exists("$did_javascript_function_run_path/tmp/$didJavaScriptFunctionRunID/file.txt")) {   // Blocks where JavaScript did run.
  // Remove the random files created.
  shell_exec("rm \"$did_javascript_function_run_path/tmp/$didJavaScriptFunctionRunID/file.txt\"");
  shell_exec("rmdir \"$did_javascript_function_run_path/tmp/$didJavaScriptFunctionRunID\"");  
  $didJavaScriptFunctionRun = 1;
 } else {
  $didJavaScriptFunctionRun = 0;
 }
 
?>