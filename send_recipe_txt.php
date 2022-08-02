<?php

  $recipe_name = $_POST['name'];
  $recipe_name_noSpaces = preg_replace('/\s+/', '_', $recipe_name);
  $recipe_name_sanitized = preg_replace('/[^a-z0-9_]/i','',$recipe_name_noSpaces); // Change to the log file name
  $recipe_file_name = $recipe_name_sanitized.'.txt';
  //$recipe_directory = '/volume1/@appstore/SqueezeCenter/HTML/EN/html/Reader/';
  $recipe_directory = ROOT.'/Users/morgan/Dropbox/Dev/TestZone/';
  echo $recipe_directory;
  $recipe_path = $recipe_directory.$recipe_file_name;
  echo $recipe_path;
  $instructions = $_POST['instructions']; // incoming message
  file_put_contents($recipe_file_name, $instructions);
  //header('Location: /recipe.php'); // redirect back to the main site



?>
