<?php

// need to set $dir and $recipe_file when using this

$recipe_path = $_SERVER['DOCUMENT_ROOT']."/Recipes/".$dir."/".$recipe_file;

$xml = simplexml_load_file($recipe_path) or die("Error: Cannot create object");

$recipeName = $xml->name;

$subname = $xml->subname;
$servings = $xml->servings;
$prepTime = $xml->prepTime;
$cookTime = $xml->cookTime;
$coolTime = $xml->coolTime;
$restTime = $xml->restTime;
$notes = $xml->notes;
$attribution = $xml->attribution;

$numTimes = 4; // number of individual timings to give (excluding total)

$prepTime_display = ""; // visible by default
$cookTime_display = ""; // visible by default
$prepTime_display = ""; // visible by default
$prepTime_display = ""; // visible by default
$prepTime_display = ""; // visible by default

if ($prepTime == "" || $prepTime == "00:00"){
  $prepTime_display = "none"; //set to invisible
  $numTimes = $numTimes - 1;
};

if ($cookTime == "" || $cookTime == "00:00"){
  $cookTime_display = "none"; //set to invisible
  $numTimes = $numTimes - 1;
};

if ($coolTime == "" || $coolTime == "00:00"){
  $coolTime_display = "none"; //set to invisible
  $numTimes = $numTimes - 1;
};

if ($restTime == "" || $restTime == "00:00"){
  $restTime_display = "none"; //set to invisible
  $numTimes = $numTimes - 1;
};

if ($numTimes < 2 ){
  $totalTime_display = "none";
};

$totalTime = strtotime($cookTime)+strtotime($prepTime)+strtotime($coolTime)+strtotime($restTime);
$totalTime = date('H:i', $totalTime);

$prepTime_attr = $xml->prepTime->attributes();

if ($prepTime_attr['icon'] == ""){
  $prepTime_icon = "prep.png";
} else {
  $prepTime_icon = $prepTime_attr['icon'];
};

$cookTime_attr = $xml->cookTime->attributes();

if ($cookTime_attr['icon'] == ""){
  $cookTime_icon = "cook.png";
} else {
  $cookTime_icon = $cookTime_attr['icon'];
};

//$ingredients = [[]];

$ingredients = [];

$numIngr = 0;

foreach ($xml->listIngredients->ingredient_item as $ingredient_item){
  if ($ingredient_item->ingredient != "" && $ingredient_item->ingredient != "undefined"){

    $ingredients[$numIngr] = array("q" => $ingredient_item->quantity,
                                      "u" => trim($ingredient_item->unit),
                                      "i" => trim($ingredient_item->ingredient),
                                      "aq" => trim($ingredient_item->quantity->alt),
                                      "au" => trim($ingredient_item->unit->alt));

    $i_quantity_display[$numIngr] = ""; // makes visible by default

    if ($ingredients[$numIngr]["q"] == "" || floatval($ingredients[$numIngr]["q"]) == 0){
      $ingredients[$numIngr]["q"] == "";
      $i_quantity_display[$numIngr] = "none"; // invisible if empty
    };

    $i_unit_display[$numIngr] = ""; // makes visible by default

    if ($ingredients[$numIngr]["u"] == ""){
      $i_unit_display[$numIngr] = "none"; // invisible if empty
    };

    // alt quantity and unit

    $i_alt_display[$numIngr] = "inline"; // makes visible by default

    $i_alt_quantity_display[$numIngr] = ""; // makes visible by default

    if ($ingredients[$numIngr]["aq"] == "" || empty($ingredients[$numIngr]["aq"])){
      $i_alt_quantity_display[$numIngr] = "none"; // invisible if empty
    };

    $i_alt_unit_display[$numIngr] = ""; // makes visible by default

    if ($ingredients[$numIngr]["au"] == ""){
      $i_alt_unit_display[$numIngr] = "none"; // invisible if empty
      if ($i_alt_quantity_display[$numIngr] == "none"){
        $i_alt_display[$numIngr] = "none"; // whole alt span invisible if both quantity and unit empty
      };
    };

    $numIngr++;
  };
};

$numInstr = 0;

$instructions = [];

foreach ($xml->listInstructions->instruction as $instruction){
  $instructions[$numInstr] = $instruction;
  $numInstr++;
};

include("recent_recipe.php");

storeRecent($recipeName, $dir."/".$recipe_file);


// following no longer required?
function startsWith($haystack, $needle){
  $length = strlen($needle);
  return (substr($haystack, 0, $length) === $needle);
};

function endsWith($haystack, $needle){
  $length = strlen($needle);
  if ($length == 0) {
    return true;
  }
  return (substr($haystack, -$length) === $needle);
};

?>
