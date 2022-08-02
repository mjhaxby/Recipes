<!DOCTYPE html>
<?php
$recipe_directory = 'Recipes';

$recipe_file = htmlspecialchars($_GET["file"]);
$dir = htmlspecialchars($_GET["dir"]);

if ($recipe_file != "new"){
  include "open_recipe.php";
} else {
  $numIngr = 1; // default number of ingredients
};

//remove any hidden files
$recipeDirectories = array_filter(scandir($recipe_directory), function($item) {
  if ($item[0] !== '.' && $item[0] !== '@'){
    $result = $item[0];
  };
  return $result;
});

$recipeDirectories = array_values($recipeDirectories); // reindex array

$numDirs = count($recipeDirectories);

?>

<html>
<head>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;1,100;1,300;1,400;1,500&display=swap" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css">
    <script src='js/recipeGenerator.js'></script>
    <script src='js/recipeIngredientImporter.js'></script>
    <script src='js/recipeConverters.js'></script>
    <script src='js/graphics.js'></script>
    <script src='js/pluralize.js'></script>
    <script src='js/autosize.min.js'></script>
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="stylesheet" type="text/css" href="css/recipe_style.css">

    <title><?php if ($file = "new"){echo "New recipe"; if ($dir != ""){echo " in ".$dir;};}else{echo $recipeName;} ?> &bull; Recipes</title>

    <!-- Favicons and touch icons -->
    <!-- For retina-display iPads -->
    <!-- <link href="/images/apple-touch-icon-precomposed.png" rel="apple-touch-icon">
    <link rel="apple-touch-icon" sizes="60x60" href="images/touch-icon-iphone.png">
    <link rel="apple-touch-icon" sizes="76x76" href="images/touch-icon-ipad.png">
    <link rel="apple-touch-icon" sizes="120x120" href="images/touch-icon-iphone-retina.png">
    <link rel="apple-touch-icon" sizes="152x152" href="images/touch-icon-ipad-retina.png">-->
    <!-- For everything else -->
    <!-- <link href="/images/favicon.png" rel="shortcut icon" type="image/png">
    <link href="/images/favicon.ico" rel="shortcut icon" type="image/x-icon">
    <link href="/images/favicon.ico" rel="icon" type="image/x-icon">-->

    <script>
      $(document).ready(function() { // this stop the enter key from submitting a form
        $(window).keydown(function(event){
          if(event.keyCode == 13) {
            event.preventDefault();
            return false;
          }
        });
      });
    </script
</head>

<body>
  <div id="wrapper">
    <form action="save_recipe.php" method="POST" onsubmit="prepareRecipeData()">
      <?php include "nav.php"?>
      <div id="dir_input_container" title="Category">
        <select id="dir_input" name="dir">
        <?php for ($i = 0; $i < $numDirs; $i++){?>
          <option value="<?php echo $recipeDirectories[$i] ?>" <?php
            if ($recipeDirectories[$i] == $dir){
              echo ' selected="selected"';
            };
            ?>><?php echo $recipeDirectories[$i];?></option>
        <?php };?>
        </select>
      </div>
      <div id="name_container">
        <label for="name">Recipe Name</label>
        <textarea id="name_input" name="name" rows=1 placeholder="Recipe name" onkeyup='nameInput(event, this)' title="Recipe name"><?php
          if ($recipe_file != "new"){echo $recipeName;};?></textarea>
        <textarea id="subname_input" name="subname" rows=1 placeholder="A sentence to introduce your recipe" onkeyup='nameInput(event, this)' title="A sentence to introduce your recipe"><?php
          if ($recipe_file != "new"){echo $subname;};?></textarea>
      </div>
      <div id="ingredients_info_container">
        <div id="ingredientsList_container">
          <div id="ingredientsList">
          <?php for ($x = 0; $x < $numIngr; $x++){?>
              <div id="i<?php echo $x?>" class="ingredient_input">
                <div class="i_remove" id="remove_ingredient_<?php echo $x?>"<?php
                  if ($x != 0){echo 'onclick="remove_ingredient('.$x.')"';}; ?>> <!--// onclick added to remove ingredient on any new ingredient in js-->
                <div class="bullet"><div class="inner-bullet"></div><div class="X-left"></div><div class="X-right"></div></div>
                </div>
                <input type="text" id="i<?php echo $x?>_q" name="i<?php echo $x?>_q" class="i_quantity" placeholder="1" value="<?php echo $ingredients[$x]["q"]?>" onpaste="import_ingredients(this, event)" onchange="emptyIfNotNumber(this)" onkeyup="ingredientInput(event, this)" onfocusout="resizeInput(this)" title="Quantity" autocomplete="off"/>
                <input type="text" id="i<?php echo $x?>_u" name="i<?php echo $x?>_u" class="i_unit" placeholder="unit" value="<?php echo $ingredients[$x]["u"]?>" onkeyup="ingredientInput(event, this)" onfocusout="resizeInput(this)" title="Units" autocomplete="off"/>
                <span id="i<?php echo $x?>_a" class="i_alternative">(<input type="text" id="i<?php echo $x?>_aq" name="i<?php echo $x?>_aq" class="i_alt_quantity" value="<?php echo $ingredients[$x]["aq"]?>" placeholder="1" onchange="emptyIfNotNumber(this)" onkeyup="ingredientInput(event, this)" onfocusout="resizeInput(this)" title="Alternative quantity" autocomplete="off"/>
                  <input type="text" id="i<?php echo $x?>_au" name="i<?php echo $x?>_au" class="i_alt_unit" placeholder="unit" value="<?php echo $ingredients[$x]["au"]?>" onkeyup="ingredientInput(event, this)" onfocusout="resizeInput(this)" title="Alternative units" autocomplete="off"/>)</span>
                <textarea id="i<?php echo $x?>_i" name="i<?php echo $x?>_i" class="i_ingredient" placeholder="ingredient" rows=1 onkeyup="ingredientInput(event, this)" title="Ingredient"><?php echo $ingredients[$x]["i"]?></textarea>
              </div>
          <?php };?>
          </div>
        </div>
        <div id="extra_info_container">
          <div id="servings_container" title="Number of servings" class="extra_info">
            <label for="servings">Serves</label>
            <input id="servings_input" name="servings" value="<?php
              if ($recipe_file != "new"){echo $servings;}else{echo "4";};
              ?>" type=number></input>
              <img class="icon" src="images/serving.png">
          </div>
          <div id="prepTime_container" title="Preparation time" class="extra_info">
          <label for="preptime">Preparation time</label>
            <input id="preptime_input" name="preptime" class=timepicker placeholder="--:--" <?php
              if ($recipe_file != "new"){echo 'value="'.$prepTime.'"';};
              ?> autocomplete="off"></input>
              <div id="preptime_icon_container" class="changeable_icon_container" onclick="selectNextIcon(preptime_icon_input)"><!--
                --><img class="icon changeable animate" id="icon_prep" src="images/prep.png"><!--//chopping board icon
                --><img class="icon changeable animate hidden" id="icon_mixer" src="images/mixer.png"><!--//electric whisk icon
                --><img class="icon changeable animate hidden" id="icon_blender" src="images/blender.png"><!--//blender icon-->
              </div>
          </div>
          <div id="restTime_container" title="Resting time" class="extra_info">
            <label for="resttime">Rest time</label>
            <input id="resttime_input" name="resttime" class=timepicker placeholder="--:--" <?php
              if ($recipe_file != "new"){echo 'value="'.$restTime.'"';};
              ?> autocomplete="off"></input>
              <img class="icon" src="images/rest.png">
          </div>
          <div id="cookTime_container" title="Cooking time" class="extra_info">
            <label for="cooktime">Cooking time</label>
            <input id="cooktime_input" name="cooktime" class=timepicker placeholder="--:--" <?php
              if ($recipe_file != "new"){echo 'value="'.$cookTime.'"';};
              ?> autocomplete="off"></input>
              <div id="cooktime_icon_container" class="changeable_icon_container" onclick="selectNextIcon(cooktime_icon_input)"><!--
                --><img class="icon changeable animate" id="icon_cook" src="images/cook.png"><!--//sauce pan icon
                --><img class="icon changeable animate hidden" id="icon_oven" src="images/oven.png"><!--//oven icon
                --><img class="icon changeable animate hidden" id="icon_microwave" src="images/microwave.png"><!--//microwave icon--><br />
              </div>
            <label for="cooktime">Cool time</label>
          </div>
          <div id="coolTime_container" title="Cooling time" class="extra_info">
            <input id="cooltime_input" name="cooltime" class=timepicker placeholder="--:--" <?php
              if ($recipe_file != "new"){echo 'value="'.$coolTime.'"';};
              ?> autocomplete="off"></input>
              <img class="icon" src="images/fridge.png">
          </div>
          <div id="totalTime_container" title="Total time" class="extra_info">
            <label for="totaltime">Total time</label>
            <input disabled id="totaltime_input" name="totaltime" class=faketimepicker placeholder="--:--" autocomplete="off"></input>
            <img class="icon" src="images/time.png"><br />
          </div>
        <!--//TO DO: animate image changes: https://www.impressivewebs.com/animate-display-block-none/ -->
        <div class="invisible">
          <select id="preptime_icon_input" name="preptime_icon">
            <option value="prep.png"<?php
              if ($prepTime_icon == "prep.png"){echo ' selected="selected"';};?>>Prep</option>
            <option value="mixer.png"<?php
              if ($prepTime_icon == "mixer.png"){echo ' selected="selected"';};?>>Mix</option>
            <option value="blender.png"<?php
              if ($prepTime_icon == "blender.png"){echo ' selected="selected"';};?>>Blend</option>
          </select>
          <select id="cooktime_icon_input" name="cooktime_icon">
            <option value="cook.png"<?php
              if ($cookTime_icon == "cook.png"){echo ' selected="selected"';};?>>Cook</option>
            <option value="oven.png"<?php
              if ($cookTime_icon == "oven.png"){echo ' selected="selected"';};?>>Bake</option>
            <option value="microwave.png"<?php
              if ($cookTime_icon == "microwave.png"){echo ' selected="selected"';};?>>Zap</option>
          </select>
        </div>
        </div>
        <div id="notes_container" title="Recipe notes">
          <label for="notes">Notes</label>
          <textarea id="notes_input" name="notes" rows=1 placeholder="Notes" onkeyup="textAreaKeyUp(event)"><?php
            if ($recipe_file != "new"){echo $notes;};
            ?></textarea>
        </div>
      </div>
      <div id="instructions_container">
        <textarea disabled id="instructions_numbers" title="Automatic recipe numbers">1</textarea>
        <label for="instructions" title="Instructions">Instructions</label>
        <textarea id="instructions_input" name="instructions" placeholder="Instructions" oninput="updateInstructionNumbers()" onkeyup="textAreaKeyUp(event)"><?php
          if ($recipe_file != "new"){
                  for ($x = 0; $x < $numInstr; $x++){
                    echo $instructions[$x];
                    if ($x < $numInstr-1){echo "\n";};
                  };
                };?></textarea>
      </div>
      <div id="attribution_container" title="Where is the recipe from?">
        <label for="attribution">Attribution</label>
        <textarea id="attribution_input" name="attribution" placeholder="Recipe by…" onkeyup="textAreaKeyUp(event)"><?php
          if ($recipe_file != "new"){echo $attribution;};
          ?></textarea>
      </div>
      <div id="save_recipe_container"><button id="save_recipe" type="submit" title="Save recipe and return to previous page">Save recipe</button>
        <div class="invisible">
          <input id="numIngredients" name="numIngredients" type="number" value="<?php echo $numIngr?>"></input>
          <input id="origFileName" name="origFileName" value="<?php echo $recipe_file?>"></input>
          <input id="origDir" name="origDir" value="<?php echo $dir?>"></input>
        </div>
      </div>
    </form>
  </div>
  <div id="foot"><a href="https://www.vecteezy.com/free-vector/recipe-icon">Recipe Icon Vectors by Vecteezy</a></div>

  <script src="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js"></script>
  <script>
  autosize(document.querySelectorAll('textarea')); // autosize (height) textareas https://github.com/jackmoore/autosize

  </script>
</body>
