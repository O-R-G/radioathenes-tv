<?
  $config = $_SERVER["DOCUMENT_ROOT"];
  $config = $config."/open-records-generator/config/config.php";
  require_once($config);

  // specific to this 'app'
  $config_dir = $root."config/";
  require_once($config_dir."url.php");
  require_once($config_dir."request.php");

  $db = db_connect("guest");

  $oo = new Objects();
  $mm = new Media();
  $ww = new Wires();
  $uu = new URL();

  if (isset($_GET["id"])) {
    $event_id = $_GET["id"];

    $media = $oo->media($event_id);
    $mediaArr = [];
    if ($media) {
      foreach($media as $m) {
        $mediaArr []= array(
          "url" => m_url($m),
          "caption" => $m['caption']);
      }
    }
    echo json_encode(array(
      "id" => $event_id,
      "media" => $mediaArr));
  }
?>
