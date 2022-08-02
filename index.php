<?php

$recipe_directory = 'Recipes';

//remove any hidden files
$recipeDirectories = array_filter(scandir($recipe_directory), function($item) {
  if ($item[0] !== '.' && $item[0] !== '@'){
    $result = $item[0];
  };
  return $result;
});

$recipeDirectories = array_values($recipeDirectories); // reindex array

$numDirs = count($recipeDirectories);

include("recent_recipe.php");

$recent = getRecent();

?>

<html>
<head>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;1,100;1,300;1,400;1,500&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="stylesheet" type="text/css" href="css/list_style.css">
    <script src="js/recipeLister.js"></script>
    <script src="js/graphics.js"></script>

    <title>Recipes</title>
</head>
<body>
  <div id="wrapper">
  <h1 style="text-align: center;">Recipes</h1>
  <div class="divider line one-line">~</div>
  <?php for ($x = 0; $x < $numDirs; $x++) {?>
    <h3 class="center_list"><a href="list_recipes.php?dir=<?php echo $recipeDirectories[$x]?>"><?php print $recipeDirectories[$x];?></a></h3>
  <?php }; ?>
  <br />
  <p class="center_list">Recent recipes</p>
  <?php
    for ($i = 0; $i < count($recent); $i++){
      $recipeFile = preg_replace("/(?:.*\/)(.+)/","$1",$recent[$i]["path"]);
      $recipeDir = preg_replace("/(.*)(?:\/.+)/","$1",$recent[$i]["path"]);?>
      <div id="recipeListItem" onmouseover="showEdit(this)" onmouseout="hideEdit(this)"><p class="center_list"><span class="edit hidden animate"><a href="edit_recipe.php?dir=<?php
      echo $recipeDir?>&file=<?php echo $recipeFile ?>">&#9998;</span><a href="view_recipe.php?dir=<?php
      echo $recipeDir?>&file=<?php echo $recipeFile ?>"><?php echo $recent[$i]["name"];
       ?></a><span class="editBalance">&#9998;</span></p></div> <!--// balance out the pencil icon on the other side so text stays centered -->
  <?php }?>
  <br />
  <?php include "new.php"?>
</div>
</body>
</html>
