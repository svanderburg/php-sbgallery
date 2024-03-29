<?php
error_reporting(E_STRICT | E_ALL);

require_once(dirname(__FILE__)."/../../vendor/autoload.php");
require_once("includes/config.php");

use Examples\LowLevel\Model\MyGallery;

$myGallery = new MyGallery($dbh);
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Gallery</title>
		<link rel="stylesheet" type="text/css" href="style.css">
		<link rel="stylesheet" type="text/css" href="gallery.css">
	</head>
	<body>
		<h1>Gallery</h1>
		<?php
		if(array_key_exists("view", $_GET) && $_GET["view"] == "1")
			\SBGallery\View\HTML\displayGallery($myGallery);
		else
			\SBGallery\View\HTML\displayEditableGallery($myGallery);
		?>
	</body>
</html>
