<?php

//ini_set('display_errors', 1); error_reporting(E_ALL);

$recipe_file = htmlspecialchars($_GET["file"]);
$dir = htmlspecialchars($_GET["dir"]);

include "open_recipe.php";

//restore new lines to notes_input
$notes = nl2br($notes);

// Put parts of ingredients in bold
for ($i = 0; $i < $numIngr; $i++){
  $ingredients[$i]["i"] = preg_replace('#\*+(.*?)\*+#', '<span class="emph">$1</span>', $ingredients[$i]["i"]);
};

$timeArray[0] = explode(':',$prepTime);
$timeArray[1] = explode(':',$restTime);
$timeArray[2] = explode(':',$cookTime);
$timeArray[3] = explode(':',$coolTime);
$timeArray[4] = explode(':',$totalTime);

for ($i = 0; $i < 5; $i++){
  if (empty($timeArray[$i])){
    $timeArray[$i][0] = "";
    $timeArray[$i][1] = "";
    $timeArray[$i][2] = "";
  } else {
    if (intval($timeArray[$i][0]) > 0){
      if (intval($timeArray[$i][0]) > 1){
        $timeArray[$i][0] = intval($timeArray[$i][0])." hours";
      } else {
        $timeArray[$i][0] = "1 hour";
      };
    } else {
      $timeArray[$i][0] = "";
    };
    if (intval($timeArray[$i][1]) > 0){
      if (intval($timeArray[$i][1]) > 1){
        $timeArray[$i][1] = intval($timeArray[$i][1])." minutes";
      } else {
        $timeArray[$i][1] = "1 minute";
      };
    } else {
      $timeArray[$i][1] = "";
    };
    if ($timeArray[$i][0] != "" && $timeArray[$i][1] != ""){
      $timeArray[$i][2] = " & ";
      $timeArray[$i][0] = preg_replace('/hour/','hr',$timeArray[$i][0]);
      $timeArray[$i][1] = preg_replace('/minute/','min',$timeArray[$i][1]);
    } else {
      $timeArray[$i][2] = "";
    };
  };
};


$prepTime = $timeArray[0][0].$timeArray[0][2].$timeArray[0][1];
$restTime = $timeArray[1][0].$timeArray[1][2].$timeArray[1][1];
$cookTime = $timeArray[2][0].$timeArray[2][2].$timeArray[2][1];
$coolTime = $timeArray[3][0].$timeArray[3][2].$timeArray[3][1];
$totalTime = $timeArray[4][0].$timeArray[4][2].$timeArray[4][1];

?>


<html>
    <head>
        <link href='http://fonts.googleapis.com/css?family=Roboto:300,300italic,400,500,600' rel='stylesheet' type='text/css'>
        <link rel="stylesheet" type="text/css" href="css/style.css">
        <!--<link rel="stylesheet" type="text/css" href="css/jeffStyle.css">-->
        <link rel="stylesheet" type="text/css" href="css/recipe_style.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src='js/pluralize.js'></script>
        <script src='js/recipeViewer.js'></script>
        <script src='js/recipeConverters.js'></script>
        <link rel="stylesheet" href="css/odometer-theme-minimal.css" /> <!--to animate numbers-->
        <script>odometerOptions = {duration: 500};</script> <!--doesn't seem to make much difference - maybe did something wrong?-->
        <script src="js/odometer.min.js"></script> <!--to animate numbers-->

        <title><?php echo $recipeName ?> &bull; Recipes</title>
    </head>
    <body>
      <div id="wrapper">
        <?php include "nav.php"?><br />
          <div id="name_container">
            <p id="name_input"><?php echo $recipeName ?></p>
            <p id="subname_input"><?php echo $subname ?></p>
          </div>

          <div id="ingredients_info_container">
            <div id="ingredientsList_container">
              <div id="ingredientsList">
                <ul>
                  <?php for ($x = 0; $x < $numIngr; $x++){
                    // for anything that looks like a heading (is bold, no quantity or unit)
                    if (strpos($ingredients[$x]["i"],'class="emph"') && $ingredients[$x]["q"] == "" && $ingredients[$x]["u"] == ""){
                    ?>
                  </ul><p id="i<?php echo $x?>" onclick="strikeingredient(this)" class="ingr_subhead">
                        <?php echo $ingredients[$x]["i"];?>
                    </p><ul>
                    <?php } else {?>

                    <li id="i<?php echo $x;?>">
                      <span class="invisible" id="i<?php echo $x?>_q_store">
                        <?php echo $ingredients[$x]["q"]?>
                      </span>
                      <span class="invisible" id="i<?php echo $x?>_u_store">
                        <?php echo $ingredients[$x]["u"]?>
                      </span>
                      <span class="odometer" style="display: <?php echo $i_quantity_display[$x]?>" id="i<?php echo $x?>_q">
                        <?php echo $ingredients[$x]["q"]?>
                      </span>
                      <span class="nonNumber" style="display: none" id="i<?php echo $x?>_q_nonNum"></span>
                      <span id="i<?php echo $x?>_u" style="display: <?php echo $i_unit_display[$x]?>">
                        <?php echo $ingredients[$x]["u"]?>
                      </span>
                      <span class="i_alternative" style="display: <?php echo $i_alt_display[$x]?>">
                        <span class="invisible" id="i<?php echo $x?>_aq_store">
                          <?php echo $ingredients[$x]["aq"]?>
                        </span>
                        <span class="invisible" id="i<?php echo $x?>_au_store">
                          <?php echo $ingredients[$x]["au"]?></span>
                        (<span class="odometer" style="display: <?php echo $i_alt_quantity_display[$x]?>" id="i<?php echo $x?>_aq">
                          <?php echo $ingredients[$x]["aq"]?>
                        </span>
                        <span class="nonNumber" style="display: none" id="i<?php echo $x?>_aq_nonNum"></span>
                        <span id="i<?php echo $x?>_au" style="display: <?php echo $i_alt_unit_display[$x]?>">
                          <?php echo $ingredients[$x]["au"]?></span>)
                      </span>
                      <span id="i<?php echo $x?>_i" onclick="strikeingredient(this)">
                        <?php echo $ingredients[$x]["i"]?>
                      </span>
                    </li>
                  <?php }};?>
                </ul>
              </div>
            </div>
            <div id="extra_info_container">
              <div id="servings_container"><span class="extra_info" id="servings"><button id="servings_adjust_plus" onclick="servingSizeChange(1)">&#9650;</button><span id="servings_show"><?php echo $servings ?></span><button id="servings_adjust_minus" onclick="servingSizeChange(-1)">&#9660;</button> servings</span><img class="icon" src="images/serving.png"></div>
              <span id="servings_store" class="invisible"><?php echo $servings ?></span>
              <div id="preptime_container" style="display: <?php echo $prepTime_display?>;"><span class="extra_info" id="preptime"><?php echo $prepTime ?></span><img class="icon" src="images/<?php echo $prepTime_icon?>"></div>
              <div id="resttime_container" style="display: <?php echo $restTime_display?>;"><span class="extra_info" id="cooltime"><?php echo $restTime ?></span><img class="icon" src="images/rest.png"></div>
              <div id="cooktime_container" style="display: <?php echo $cookTime_display?>;"><span class="extra_info" id="cooktime"><?php echo $cookTime ?></span><img class="icon" src="images/<?php echo $cookTime_icon?>"></div>
              <div id="cooltime_container" style="display: <?php echo $coolTime_display?>;"><span class="extra_info" id="cooltime"><?php echo $coolTime ?></span><img class="icon" src="images/fridge.png"></div>
              <div id="totaltime_container" style="display: <?php echo $totalTime_display?>;"><span class="smalltext" id="totalTime_viewLabel">total </span><span class="extra_info" id="totaltime"><?php echo $totalTime ?></span><img class="icon" src="images/time.png"></div>
            </div>
            <div id="notes_container"><p id="notes"><?php echo $notes ?></p></div>
          </div>
          <div id="instructions_container">
            <ol>
            <?php for ($x = 0; $x < $numInstr; $x++){?>
              <li><?php echo $instructions[$x]; ?>
            <?php };?>
            </ol>
          </div>
          <div id="attribution"><p><?php echo $attribution?></p></div>
      </div>
      <span id="numIngr" class="invisible"><?php echo $numIngr;?></span>
      <div id="foot"><a href="https://www.vecteezy.com/free-vector/recipe-icon">Recipe Icon Vectors by Vecteezy</a></div>
    </body>

</html>
