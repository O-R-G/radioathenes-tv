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
if ($uri[1] == "events") {
  require_once("views/event.php");
} else {
  require_once("views/home.php");
}
require_once("views/foot.php");
?>
