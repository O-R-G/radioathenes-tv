<?
$uri = explode('/', $_SERVER['REQUEST_URI']);
$view = "views/";

/* ------------------------------------------------------
        handle url:
        + /dev > gyroscope (plus hide the clock)
        + /thx > download
        + everything else > object-fullscreen
------------------------------------------------------ */

// show the things
require_once("views/head.php");
require_once("views/utils.php");
require_once("views/menu.php");
if ($uri[1] == "events") {
  require_once("views/event.php");
} elseif($uri[1] == "all") {
  require_once("views/all.php");
} elseif($uri[1] == "credits") {
  require_once("views/credits.php");
} else {
  require_once("views/home.php");
}
require_once("views/foot.php");
?>
