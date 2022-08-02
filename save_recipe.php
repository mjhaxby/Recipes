<?php
  //Name of folder where Recipes are stored
  $recipe_directory = 'Recipes';

  //Get the recipe's name
  $recipe_name = $_POST['name'];
  //Create a sensible filename
  $recipe_name_noSpaces = preg_replace('/\s+/', '_', $recipe_name); //Swap any whitespace for underscore
  $recipe_name_sanitized = preg_replace('/[^a-z0-9_]/i','',$recipe_name_noSpaces); // Remove any non-alphanumerical characters
  $recipe_file_name = $recipe_name_sanitized.'.xml';

  // get original file name and directory for editing existing recipes
  $origFileName = $_POST['origFileName'];
  $origDir = $_POST['origDir'];
  $orig_path = $recipe_directory. DIRECTORY_SEPARATOR .$origDir. DIRECTORY_SEPARATOR .$origFileName;

  //for existing file, create a back up in case of problems saving
  //also this will avoid having duplicates if the file is going to be renamed or moved to a different directory
  //also only does back up that the original file exists, in case the user deleted it or renamed it while editing
  if($origFileName != "new" && file_exists($orig_path)){
    $temp_path = $recipe_directory. DIRECTORY_SEPARATOR .$origDir. DIRECTORY_SEPARATOR .".backup_".$origFileName;
    rename($orig_path, $temp_path);
  };

  //for debugging
  //ini_set('display_errors', 1); error_reporting(E_ALL);

  //Where to store the recipe
  $recipe_subdirectory = $_POST['dir'];
  $recipe_path = $recipe_directory. DIRECTORY_SEPARATOR .$recipe_subdirectory. DIRECTORY_SEPARATOR .$recipe_file_name;

// Append _2 (or higher) if file already exists
$count = 1; //to append to duplicate file
while (file_exists($recipe_path)) {
  if ($count == 99){
     die("Error: Looking for non-existing file path looped 99 times. Pressumed bug and save cancelled.");
  }
  $count++; //start from 2, add one each time
  $recipe_path = preg_replace('/(_\d+)?\.xml/','',$recipe_path); // take off existing _X.xml or .xml
  $recipe_path = $recipe_path.'_'.$count.'.xml'; // append number and file extension
};

  // some easy bits of information to grab
  $recipe_subname = $_POST['subname'];
  $recipe_notes = $_POST['notes'];
  $attribution = $_POST['attribution'];
  $recipe_servings = $_POST['servings'];
  $recipe_prepTime = $_POST['preptime'];
  $recipe_cookTime = $_POST['cooktime'];
  $recipe_coolTime = $_POST['cooltime'];
  $recipe_restTime = $_POST['resttime'];

  $prepTime_icon = $_POST['preptime_icon'];
  $cookTime_icon = $_POST['cooktime_icon'];

  $numIngr = intval($_POST['numIngredients']);

  $recipe_notes = str_replace("&#13;","/n", $recipe_notes);

  $ingrs=[];

  for ($x = 0; $x <=  $numIngr-1; $x++){ // gathering ingredients
    if (!empty($_POST['i'.$x.'_i'])){ // will only save if there is an ingredient item
      $ingrs[$x][0] = $_POST['i'.$x.'_q']; //quantities
      $ingrs[$x][1] = $_POST['i'.$x.'_u']; //units
      $ingrs[$x][2] = $_POST['i'.$x.'_i']; //ingredient itself (sometimes known as ingredient_item)
      $ingrs[$x][3] = $_POST['i'.$x.'_aq']; //alt quantities
      $ingrs[$x][4] = $_POST['i'.$x.'_au']; //alt units
    } else {
      $numIngr = $numIngr - 1; //if it was a false ingredient, we need to subtrack this from the total number
    };

  };

  //Instructions into an array
  $instructions = explode("\n",str_replace("\r", "", $_POST['instructions'])); // removing /r because some platforms to /r/n for a new line
  $numInstr = count($instructions); //How many instructions

  //Create the XML

  $xw = xmlwriter_open_memory();
  xmlwriter_set_indent($xw, 1);
  $res = xmlwriter_set_indent_string($xw, ' ');

  xmlwriter_start_document($xw, '1.0', 'UTF-8');

  xmlwriter_start_element($xw, 'recipe'); // <recipe> // XML files need to be enclosed in a single root

  xmlwriter_start_element($xw, 'name'); // <name>
  xmlwriter_text($xw, $recipe_name); // recipe name
  xmlwriter_end_element($xw); // </name>

  xmlwriter_start_element($xw, 'subname'); // <name>
  xmlwriter_text($xw, $recipe_subname); // recipe name
  xmlwriter_end_element($xw); // </name>

  xmlwriter_start_element($xw, 'servings'); // <servings>
  xmlwriter_text($xw, $recipe_servings); //
  xmlwriter_end_element($xw); // </servings>

  xmlwriter_start_element($xw, 'prepTime'); // <prepTime
  xmlwriter_start_attribute($xw, 'icon'); // icon=
  xmlwriter_text($xw, $prepTime_icon); // X.png
  xmlwriter_end_attribute($xw); //>
  xmlwriter_text($xw, $recipe_prepTime);
  xmlwriter_end_element($xw); // </prepTime>

  xmlwriter_start_element($xw, 'cookTime'); // <cookTime
  xmlwriter_start_attribute($xw, 'icon'); // icon=
  xmlwriter_text($xw, $cookTime_icon); // X.png
  xmlwriter_end_attribute($xw); //>
  xmlwriter_text($xw, $recipe_cookTime);
  xmlwriter_end_element($xw); // </cookTime>

  xmlwriter_start_element($xw, 'restTime'); // <restTime>
  xmlwriter_text($xw, $recipe_restTime);
  xmlwriter_end_element($xw); // </prepTime_i>

  xmlwriter_start_element($xw, 'coolTime'); // <coolTime>
  xmlwriter_text($xw, $recipe_coolTime);
  xmlwriter_end_element($xw); // </coolTime>

  xmlwriter_start_element($xw, 'notes'); // <notes>
  xmlwriter_text($xw, $recipe_notes); //
  xmlwriter_end_element($xw); // </notes>

  xmlwriter_start_element($xw, 'attribution'); // <attribution>
  xmlwriter_text($xw, $attribution); //
  xmlwriter_end_element($xw); // </attribution>

  xmlwriter_start_element($xw, 'listIngredients'); // <listIngredients>

  $counter = 0; //we'll use this for ID'ing the ingredients


  for ($x = 0; $x < $numIngr; $x++){
    xmlwriter_start_element($xw, 'ingredient_item'); // <ingredient
    xmlwriter_start_attribute($xw, 'id'); // id=
    xmlwriter_text($xw, $x); // id number
    xmlwriter_end_attribute($xw); //>

    xmlwriter_start_element($xw, 'quantity'); // <quantity>
    xmlwriter_text($xw, $ingrs[$x][0]);
    if (!empty($ingrs[$x][3])){
      xmlwriter_start_element($xw, 'alt'); // <alt>
      xmlwriter_text($xw, $ingrs[$x][3]); // alternative quantity
      xmlwriter_end_element($xw); // </alt>
    }
    xmlwriter_end_element($xw); // </quantity>

    xmlwriter_start_element($xw, 'unit'); // <unit>
    xmlwriter_text($xw, $ingrs[$x][1]);
    if (!empty($ingrs[$x][4])){
      xmlwriter_start_element($xw, 'alt'); // <alt>
      xmlwriter_text($xw, $ingrs[$x][4]); // alternative unit
      xmlwriter_end_element($xw); // </alt>
    }
    xmlwriter_end_element($xw); // </unit>

    xmlwriter_start_element($xw, 'ingredient'); // <item>
    xmlwriter_text($xw, $ingrs[$x][2]);
    xmlwriter_end_element($xw); // </item>

    xmlwriter_end_element($xw); // </ingredient_item>

  };

  xmlwriter_end_element($xw); // </listIngredients>

  xmlwriter_start_element($xw, 'listInstructions'); // <listInstructions>

  for ($x = 0; $x <  $numInstr; $x++){
    xmlwriter_start_element($xw, 'instruction'); // <instruction>
    xmlwriter_start_attribute($xw, 'id'); // id=
    xmlwriter_text($xw, $x);
    xmlwriter_end_attribute($xw); //>
    xmlwriter_text($xw, $instructions[$x]);
    xmlwriter_end_element($xw); // </instruction>
  };

  xmlwriter_end_element($xw); // </listInstructions>

  xmlwriter_end_element($xw); // </recipe>

  xmlwriter_end_document($xw);

  file_put_contents($recipe_path, xmlwriter_output_memory($xw));

  // delete old file if there was one
  if($origFileName != "new"){
    unlink($temp_path);
  };

  header('Location: /list_recipes.php?dir='.$recipe_subdirectory); // redirect to index
?>
