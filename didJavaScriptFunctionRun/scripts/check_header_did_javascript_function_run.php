<?php
 // check_header_did_javascript_function_run
 // Check if the php was called via the command line using curl.
 // Sets $user_agent_did_javascript_function_run to 1 when it was, and 0 otherwise.

 $user_agent_did_javascript_function_run = 0;
 if (!function_exists("check_header_did_javascript_function_run")) {
  function check_header_did_javascript_function_run() {
   global $user_agent_did_javascript_function_run;
   $user_agent_did_javascript_function_run = 0;
   $head_val = isset($_SERVER["HTTP_USER_AGENT"]) ? (string) $_SERVER["HTTP_USER_AGENT"] : "";
   if (stripos($head_val, "curl") !== false) {
    $user_agent_did_javascript_function_run = 1;
   }
  }
 }

?>