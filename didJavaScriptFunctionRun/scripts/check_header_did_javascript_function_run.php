<?php
 // check_header_did_javascript_function_run
 // Check if the php was called via the command line using curl.
 
 $user_agent_did_javascript_function_run = 0;
 function check_header_did_javascript_function_run() {
  global $user_agent_did_javascript_function_run;
  $header_did_javascript_function_run = getallheaders();  
  foreach ($header_did_javascript_function_run as $head_prop => $head_val) {
   if ($head_prop == "User-Agent") {  
    $user_agent_did_javascript_function_run = strpos($head_val, "url");  
   }  
  }
 }
 
?>