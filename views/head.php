<?
// path to config file
$config = $_SERVER["DOCUMENT_ROOT"];
$config = $config."/open-records-generator/config/config.php";
require_once($config);

// specific to this 'app'
$config_dir = $root."config/";
require_once($config_dir."url.php");
require_once($config_dir."request.php");
require_once(__DIR__ . '/../static/php/SiteMeta.php');

$db = db_connect("guest");

$oo = new Objects();
$mm = new Media();
$ww = new Wires();
$uu = new URL();

// $rr = new Request();

// self
$id = $uu->id ?? 0;
$ids = $id === 0 ? [] : $uu->ids;
$item = $oo->get($id);
$pageMeta = array();
if($item['address2']) {
	$pageMeta['description'] = $item['address2'];
}
if($item['address1']) {
	$pageMeta['keywords'] = $item['address1'];
}
$siteMeta = new SiteMeta($db, ['system', 'site-meta'], $pageMeta);

$nav = $oo->nav($ids);

?>
<!DOCTYPE html>
<html>
	<head>
		<?php 
			echo $siteMeta->generate(); 
		?>
		<link rel="stylesheet" href="<? echo $host; ?>static/fonts/beeb/stylesheet.css">
		<link rel="stylesheet" href="<? echo $host; ?>static/css/global.css">
		<link rel="apple-touch-icon" href="<? echo $host; ?>media/png/touchicon.png" />
	</head>
<body>
