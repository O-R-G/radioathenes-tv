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
$siteMeta = new SiteMeta($db, ['system', 'site-meta']);
// $rr = new Request();

// self
$id = $uu->id ?? 0;
$ids = $id === 0 ? [] : $uu->ids;
$item = $oo->get($id);
// $name = ltrim(strip_tags($item["name1"]), ".");

// document title
// var_dump($uu->id);
// $item = $oo->get($uu->id);
// $title = $item["name1"];
$nav = $oo->nav($ids);

?>
<!DOCTYPE html>
<html>
	<head>
		<!-- <title><? echo $title; ?></title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="mobile-web-app-capable" content="yes">
		<meta name="description" content= "Radioathenes, Radioathenes, <?= $title; ?>" >
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"> -->
		<?php 
			echo $siteMeta->generate(); 
		?>
		<link rel="stylesheet" href="<? echo $host; ?>static/fonts/beeb/stylesheet.css">
		<link rel="stylesheet" href="<? echo $host; ?>static/css/global.css">
		<link rel="apple-touch-icon" href="<? echo $host; ?>media/png/touchicon.png" />
	</head>
<body>
