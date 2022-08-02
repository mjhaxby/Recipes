<?php

function storeRecent($name, $file){

  $recent = [];

  $recent[0] = array("path" => $file, "name" => $name);
  $recent[1] = array("path" => "", "name" => "");
  $recent[2] = array("path" => "", "name" => "");
  $recent[3] = array("path" => "", "name" => "");

  if (file_exists("recent_recipes.xml")){
    $old_xml = simplexml_load_file("recent_recipes.xml") or die("Error: Cannot create object");
    // get latest two recents and put them in the later positions in the xml file
    $recent[1]["path"] = $old_xml->recent0->path;
    $recent[1]["name"] = $old_xml->recent0->name;
    $recent[2]["path"] = $old_xml->recent1->path;
    $recent[2]["name"] = $old_xml->recent1->name;
    $recent[3]["path"] = $old_xml->recent2->path;
    $recent[3]["name"] = $old_xml->recent2->name;

    if ($recent[1]["path"] == $recent[0]["path"]){
      return false;
    }
    if ($recent[2]["path"] == $recent[0]["path"]){
      $recent[2]["name"] = $old_xml->recent3->name;
      $recent[2]["path"] = $old_xml->recent3->path;
    }
  }

  $xw = xmlwriter_open_memory();
  xmlwriter_set_indent($xw, 1);
  $res = xmlwriter_set_indent_string($xw, ' ');

  xmlwriter_start_document($xw, '1.0', 'UTF-8');

  xmlwriter_start_element($xw, 'recents'); // <recents> // XML files need to be enclosed in a single root

  xmlwriter_start_element($xw, 'recent0'); // <recent0>
  xmlwriter_start_element($xw, 'name'); // <name>
  xmlwriter_text($xw, $recent[0]["name"]); // most recently opened recipe name
  xmlwriter_end_element($xw); // </name>
  xmlwriter_start_element($xw, 'path'); // <path>
  xmlwriter_text($xw, $recent[0]["path"]); // most recently opened recipe path
  xmlwriter_end_element($xw); // </path>
  xmlwriter_end_element($xw); // </recent0>

  xmlwriter_start_element($xw, 'recent1'); // <recent1>
  xmlwriter_start_element($xw, 'name'); // <name>
  xmlwriter_text($xw, $recent[1]["name"]); // most recently opened recipe name
  xmlwriter_end_element($xw); // </name>
  xmlwriter_start_element($xw, 'path'); // <path>
  xmlwriter_text($xw, $recent[1]["path"]);
  xmlwriter_end_element($xw); // </path>
  xmlwriter_end_element($xw); // </recent1>

  xmlwriter_start_element($xw, 'recent2'); // <recent2>
  xmlwriter_start_element($xw, 'name'); // <name>
  xmlwriter_text($xw, $recent[2]["name"]); // most recently opened recipe name
  xmlwriter_end_element($xw); // </name>
  xmlwriter_start_element($xw, 'path'); // <path>
  xmlwriter_text($xw, $recent[2]["path"]);
  xmlwriter_end_element($xw); // </path>
  xmlwriter_end_element($xw); // </recent2>

  xmlwriter_start_element($xw, 'recent3'); // <recent3>
  xmlwriter_start_element($xw, 'name'); // <name>
  xmlwriter_text($xw, $recent[3]["name"]); // most recently opened recipe name
  xmlwriter_end_element($xw); // </name>
  xmlwriter_start_element($xw, 'path'); // <path>
  xmlwriter_text($xw, $recent[3]["path"]);
  xmlwriter_end_element($xw); // </path>
  xmlwriter_end_element($xw); // </recent3>

  xmlwriter_end_element($xw); // </recents>

  xmlwriter_end_document($xw);

  file_put_contents("recent_recipes.xml", xmlwriter_output_memory($xw));

}

function getRecent(){

  if (file_exists("recent_recipes.xml")){
    $xml = simplexml_load_file("recent_recipes.xml") or die("Error: Cannot create object");
    // get latest two recents and put them in the later positions in the xml file
    $recent[0]["path"] = $xml->recent0->path;
    $recent[0]["name"] = $xml->recent0->name;
    $recent[1]["path"] = $xml->recent1->path;
    $recent[1]["name"] = $xml->recent1->name;
    $recent[2]["path"] = $xml->recent2->path;
    $recent[2]["name"] = $xml->recent2->name;

    return $recent;
  }

}

?>
