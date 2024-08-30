<?php
 // clean_random_character_function
 // Function used several times to clean out special characters from random character check.
 
 function clean_random_character_function($cleanChar) {
  $cleanChar = trim($cleanChar);
  $cleanChar = htmlspecialchars($cleanChar);
  $cleanChar = preg_replace('/[.\/=$\*]/', "", $cleanChar);  
  return $cleanChar;
 }

?>