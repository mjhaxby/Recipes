<?php

$listDir = htmlspecialchars($_GET["dir"]); // not using $dir or it will show up in the nav bar

$recipe_directory = 'Recipes'.'/'.$listDir;

//remove any hidden files as well as directories
$recipeFiles = array_filter(scandir($recipe_directory), function($item) {
    if ($item[0] !== '.' && $item[0] !== '@'){
      $result = $item[0];
    };
    return $result;
});

$recipeFiles = array_values($recipeFiles); // reindex array

$recipeNames = array();

for ($x = 0; $x <= count($recipeFiles)-1; $x++){
  $xml = simplexml_load_file($recipe_directory. DIRECTORY_SEPARATOR .$recipeFiles[$x]) or die("Error: Cannot create object");
  $recipeNames[$x] = $xml->name;
};

$numRecipes = count($recipeNames);

?>

<html>
<head>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;1,100;1,300;1,400;1,500&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="stylesheet" type="text/css" href="css/list_style.css">
    <script src="js/recipeLister.js"></script>
    <script src="js/graphics.js"></script>

    <title><?php echo $listDir ?> &bull; Recipes</title>
</head>
<body>
  <div id="wrapper">
  <?php include "nav.php"?><br />
  <h2><?php echo $listDir ?></h2>
  <div class="divider line one-line">~</div>
  <?php if ($numRecipes == 0){?>
    <p class="center_list">No recipes here</p>
  <?php } else {
   for ($x = 0; $x < $numRecipes; $x++){ ?>
    <div id="recipeListItem" onmouseover="showEdit(this)" onmouseout="hideEdit(this)"><p class="center_list"><span class="edit hidden animate"><a href="edit_recipe.php?dir=<?php
    echo $listDir;?>&file=<?php echo $recipeFiles[$x];?>">&#9998;</span><a href="view_recipe.php?dir=<?php
     echo $listDir?>&file=<?php echo $recipeFiles[$x]?>"><?php echo $recipeNames[$x];
     ?></a><span class="editBalance">&#9998;</span></p></div> <!--// balance out the pencil icon on the other side so text stays centered -->
  <?php };}; ?>
  <br />
  <?php include "new.php"?>
</div>
</body>
</html>
