<?php
  // delete_random_file
  // Delete random file and folder created from AJAX call when page is loaded if no button or inputs clicked.

  // Include cleaning function.
  include_once(__DIR__ . "/clean_random_character_function.php");
  // include check user agent.
  include_once(__DIR__ . "/check_header_did_javascript_function_run.php");
  check_header_did_javascript_function_run();

  header("Content-Type: text/plain; charset=utf-8");
  header("Cache-Control: no-store");
  header("X-Content-Type-Options: nosniff");

  if ($user_agent_did_javascript_function_run == 0) {
   // Only the 8 letters made by didJavaScriptFunctionRun.js are accepted.
   $out_query = isset($_SERVER["QUERY_STRING"]) ? (string) $_SERVER["QUERY_STRING"] : "";
   if (preg_match('/^[A-Za-z]{8}$/D', $out_query) !== 1) {
    http_response_code(400);
    exit;
   }

   $out_q_folder = dirname(__DIR__) . "/tmp/" . $out_query;

   if (is_file($out_q_folder . "/file.txt")) {
    @unlink($out_q_folder . "/file.txt");
    @rmdir($out_q_folder);
   }
  }
?>
