<?php
 // clean_random_character_function
 // Validate the random characters or data passed to the support scripts.
 // Returns the trimmed value when it holds only letters, digits and underscores
 // (1 to 160 characters), and an empty string for anything else.

 if (!function_exists("clean_random_character_function")) {
  function clean_random_character_function($cleanChar) {
   if (!is_string($cleanChar)) {
    return "";
   }
   $cleanChar = trim($cleanChar);
   if (preg_match('/^[A-Za-z0-9_]{1,160}$/D', $cleanChar) === 1) {
    return $cleanChar;
   }
   return "";
  }
 }

?>