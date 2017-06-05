<?php
error_reporting(E_STRICT | E_ALL);
set_include_path("./lib/sbdata:./lib/sbeditor:./lib/sbgallery");

require_once("includes/model/MyGallery.class.php");
require_once("gallery/view/html/multiplepictures.inc.php");

/* Construct picture model from the gallery model */
$myGallery = new MyGallery();
$album = $myGallery->constructAlbum();

/* Controller */
if(array_key_exists("__operation", $_REQUEST) && $_REQUEST["__operation"] == "insert_multiple_pictures")
{
	$album->insertMultiplePictures($_REQUEST["ALBUM_ID"], "Image");
	header("Location: album.php?ALBUM_ID=".$_REQUEST["ALBUM_ID"]);
	exit;
}
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Pictures</title>
		<link rel="stylesheet" type="text/css" href="style.css">
	</head>
	<body>
		<h1>Pictures</h1>

		<?php displayPicturesUploader($_REQUEST["ALBUM_ID"]); ?>
	</body>
</html>
