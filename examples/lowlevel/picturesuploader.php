<?php
error_reporting(E_STRICT | E_ALL);

require_once(dirname(__FILE__)."/../../vendor/autoload.php");
require_once("includes/config.php");

use Examples\LowLevel\Model\MyGallery;

/* Construct picture model from the gallery model */
$myGallery = new MyGallery($dbh);

/* Controller */
$error = null;

try
{
	if(array_key_exists($myGallery->settings->operationParam, $_REQUEST))
	{
		$operationParam = $_REQUEST[$myGallery->settings->operationParam];

		if($operationParam == "insert_multiple_pictures")
		{
			$album = $myGallery->queryAlbum($_REQUEST["ALBUM_ID"]);
			$album->insertMultiplePictures("Image");
			header("Location: album.php?".http_build_query(array(
				"ALBUM_ID" => $_REQUEST["ALBUM_ID"]
			), "", null, PHP_QUERY_RFC3986));
			exit();
		}
		else
			$error = "Unknown operation: ".$error;
	}
	else
	{
		$album = $myGallery->queryAlbum($_REQUEST["ALBUM_ID"]);
		$picturesUploader = $album->constructPicturesUploader();
	}
}
catch(Exception $ex)
{
	$error = $ex->getMessage();
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
			\SBGallery\View\HTML\displayPicturesUploader($picturesUploader);
		else
			print($error);
		?>
	</body>
</html>
