<?php
 // template_check
 // PHP template to check that a javascript function has run in order to send confirmation email.
 
 $_php_variable = "CHANGE_NAME";
 $_did_javascript_function_run_Confirmation = file_get_contents($_php_variable);
 
 // Echo text file name on verification.
 if ($_did_javascript_function_run_Confirmation == "") {
  echo $_php_variable;
 }

 `rm $_php_variable`;
 
?>