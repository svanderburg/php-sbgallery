<?php
error_reporting(E_STRICT | E_ALL);

require_once(dirname(__FILE__)."/../../vendor/autoload.php");

use Examples\LowLevel\Model\MyGallery;

/* Construct picture model from the gallery model */
$myGallery = new MyGallery();
$album = $myGallery->constructAlbum();

/* Controller */
$error = null;

if(array_key_exists("__operation", $_REQUEST) && $_REQUEST["__operation"] == "insert_multiple_pictures")
{
	try
	{
		$album->insertMultiplePictures($_REQUEST["ALBUM_ID"], "Image");
		header("Location: album.php?".http_build_query(array(
			"ALBUM_ID" => $_REQUEST["ALBUM_ID"]
		), "", null, PHP_QUERY_RFC3986));
		exit;
	}
	catch(Exception $ex)
	{
		$error = $ex->getMessage();
	}
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

		<?php
		if($error === null)
			\SBGallery\View\HTML\displayPicturesUploader($_REQUEST["ALBUM_ID"]);
		else
			print($error);
		?>
	</body>
</html>
