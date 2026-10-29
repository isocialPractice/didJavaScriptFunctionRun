<?php
  // make_random_file
  // Make the random folder and file the support scripts use to verify JavaScript ran.

  // Include cleaning function.
  include_once(__DIR__ . "/clean_random_character_function.php");
  // include check user agent.
  include_once(__DIR__ . "/check_header_did_javascript_function_run.php");
  check_header_did_javascript_function_run();

  // Folder holding the random folders, and how long one may live in seconds.
  $tmp_did_javascript_function_run      = dirname(__DIR__) . "/tmp/";
  $tmp_life_did_javascript_function_run = 1800;

  // Remove one tmp entry: a file, a link, or a folder and everything in it.
  // Links are removed, never followed.
  if (!function_exists("remove_tmp_entry_did_javascript_function_run")) {
   function remove_tmp_entry_did_javascript_function_run($entry_path) {
    if (is_link($entry_path)) {
     if (!@unlink($entry_path)) { @rmdir($entry_path); }
     return;
    }
    if (is_dir($entry_path)) {
     $entry_list = @scandir($entry_path);
     if ($entry_list !== false) {
      foreach ($entry_list as $entry_name) {
       if ($entry_name === "." || $entry_name === "..") { continue; }
       remove_tmp_entry_did_javascript_function_run($entry_path . "/" . $entry_name);
      }
     }
     @rmdir($entry_path);
     return;
    }
    @unlink($entry_path);
   }
  }

  // Remove tmp entries older than the time limit. README.md, the files that protect
  // the folder, editor metadata (_notes), and the reserved state folder are kept;
  // everything else in tmp was made by these scripts.
  if (!function_exists("prune_tmp_did_javascript_function_run")) {
   function prune_tmp_did_javascript_function_run($tmp_folder, $time_limit) {
    $entry_list = @scandir($tmp_folder);
    if ($entry_list === false) { return; }
    $keep_list      = array(".", "..", "README.md", "index.html", ".htaccess", "_notes", "state");
    $oldest_allowed = time() - $time_limit;
    foreach ($entry_list as $entry_name) {
     if (in_array($entry_name, $keep_list, true)) { continue; }
     $entry_stat = @lstat($tmp_folder . $entry_name);
     if ($entry_stat !== false && $entry_stat["mtime"] < $oldest_allowed) {
      remove_tmp_entry_did_javascript_function_run($tmp_folder . $entry_name);
     }
    }
   }
  }

  header("Content-Type: text/plain; charset=utf-8");
  header("Cache-Control: no-store");
  header("X-Content-Type-Options: nosniff");

  if ($user_agent_did_javascript_function_run == 0) {
   // Only the 8 letters made by didJavaScriptFunctionRun.js are accepted, and anything
   // else is refused before the file system is touched.
   $query = isset($_SERVER["QUERY_STRING"]) ? (string) $_SERVER["QUERY_STRING"] : "";
   if (preg_match('/^[A-Za-z]{8}$/D', $query) !== 1) {
    http_response_code(400);
    exit;
   }

   // Remove random folders that have outlived the time limit.
   prune_tmp_did_javascript_function_run($tmp_did_javascript_function_run, $tmp_life_did_javascript_function_run);

   // Make the random folder and file, or renew their time if they already exist.
   $q_folder = $tmp_did_javascript_function_run . $query;
   $q_file   = $q_folder . "/file.txt";
   if (is_file($q_file)) {
    @touch($q_file);
    @touch($q_folder);
   } else {
    if (!is_dir($q_folder)) {
     @mkdir($q_folder, 0755);
    }
    @file_put_contents($q_file, $query . "\n", LOCK_EX);
   }

   // Confirm the random file exists.
   if (is_file($q_file)) {
    echo "yes";
   }
  }

?>
