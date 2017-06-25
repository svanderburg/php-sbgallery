<?php
error_reporting(E_STRICT | E_ALL);
set_include_path("./lib/sbdata:./lib/sbeditor:./lib/sbgallery");

require_once("includes/model/MyGallery.class.php");
require_once("gallery/view/html/gallery.inc.php");

$myGallery = new MyGallery();
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Gallery</title>
		<link rel="stylesheet" type="text/css" href="style.css">
	</head>
	<body>
		<h1>Gallery</h1>
		<?php
		displayGalleryBreadcrumbs($myGallery, $_SERVER["PHP_SELF"]);

		if(array_key_exists("view", $_GET) && $_GET["view"] == "1")
			displayGallery($myGallery);
		else
			displayEditableGallery($myGallery);
		?>
	</body>
</html>
