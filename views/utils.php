<?
function getEventsID($oo, $root) {
  $children = $oo->children($root);
  foreach($children as $child) {
    $name =  strtolower($child["name1"]);
    if ($name == "events") {
      return $child['id'];
    }
  }
}

function getNoiseGifs($oo, $root) {
  $children = $oo->children($root);
  $systemId = None;
  $gifsId = None;

  foreach($children as $child) {
    $name =  strtolower($child["name1"]);
    if ($name == "_system") {
       $systemId = $child['id'];
       break;
    }
  }

  $systemChildren = $oo->children($systemId);
  foreach($systemChildren as $child) {
    $name =  strtolower($child["name1"]);
    if ($name == "gifs") {
       $gifsId = $child['id'];
       break;
    }
  }

  return $oo->media($gifsId);
}

// most recent chronological sort
function date_sort($a, $b) {
  return strtotime($b['begin']) - strtotime($a['begin']);
}

function id_sort($a, $b) {
  return $a['id'] - $b['id'];
}

?>
