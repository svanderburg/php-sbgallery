<?php
error_reporting(E_STRICT | E_ALL);

require_once(dirname(__FILE__)."/../../vendor/autoload.php");

use Examples\LowLevel\Model\MyGallery;

/* Construct picture model from the gallery model */
$myGallery = new MyGallery();
$album = $myGallery->constructAlbum();
$picture = $album->constructPicture($_REQUEST["ALBUM_ID"]);

/* Display picture controller */
try
{
	if(!array_key_exists("ALBUM_ID", $_REQUEST))
		throw new Exception("No album id provided!");
	else if(!array_key_exists("PICTURE_ID", $_REQUEST))
		$picture->create($_REQUEST["ALBUM_ID"]);
	else if(array_key_exists("__operation", $_REQUEST))
	{
		if($_REQUEST["__operation"] == "insert_picture")
		{
			if($picture->insert($_REQUEST))
			{
				header("Location: ".$_SERVER["PHP_SELF"]."?ALBUM_ID=".$picture->entity["ALBUM_ID"]."&PICTURE_ID=".$picture->entity["PICTURE_ID"]);
				exit;
			}
		}
		else if($_REQUEST["__operation"] == "update_picture")
		{
			if($picture->update($_REQUEST))
			{
				header("Location: ".$_SERVER["PHP_SELF"]."?ALBUM_ID=".$picture->entity["ALBUM_ID"]."&PICTURE_ID=".$picture->entity["PICTURE_ID"]);
				exit;
			}
		}
		else if($_REQUEST["__operation"] == "remove_picture")
		{
			$picture->remove($_REQUEST["PICTURE_ID"], $_REQUEST["ALBUM_ID"]);

			header("Location: ".$_SERVER["HTTP_REFERER"]);
			exit;
		}
		else if($_REQUEST["__operation"] == "remove_picture_image")
		{
			$picture->removePictureImage($_REQUEST["PICTURE_ID"], $_REQUEST["ALBUM_ID"]);

			header("Location: ".$_SERVER["HTTP_REFERER"]);
			exit;
		}
		else if($_REQUEST["__operation"] == "setasthumbnail_picture")
		{
			$picture->setAsThumbnail($_REQUEST["PICTURE_ID"], $_REQUEST["ALBUM_ID"]);

			header("Location: ".$_SERVER["HTTP_REFERER"]);
			exit;
		}
		else if($_REQUEST["__operation"] == "moveleft_picture")
		{
			$picture->moveLeft($_REQUEST["PICTURE_ID"], $_REQUEST["ALBUM_ID"]);

			header("Location: ".$_SERVER["HTTP_REFERER"]);
			exit;
		}
		else if($_REQUEST["__operation"] == "moveright_picture")
		{
			$picture->moveRight($_REQUEST["PICTURE_ID"], $_REQUEST["ALBUM_ID"]);

			header("Location: ".$_SERVER["HTTP_REFERER"]);
			exit;
		}
		else
			throw new Exception("Unknown operation: ".$_REQUEST["__operation"]);
	}
	else
		$picture->view($_REQUEST["PICTURE_ID"], $_REQUEST["ALBUM_ID"]);
	
	$error = null;
}
catch(Exception $ex)
{
	header("HTTP/1.1 404 Not Found");
	$error = $ex->getMessage();
}
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Picture</title>
		<link rel="stylesheet" type="text/css" href="style.css">
		<script type="text/javascript" src="scripts/htmleditor.js"></script>
	</head>
	<body>
		<h1>Picture</h1>
		<?php
		if($error === null)
		{
			/* View the picture */
			\SBGallery\View\HTML\displayPictureBreadcrumbs($picture, "gallery.php", "album.php", "picture.php");

			if(array_key_exists("view", $_GET) && $_GET["view"] == "1")
				\SBGallery\View\HTML\displayPicture($picture);
			else
			{
				\SBGallery\View\HTML\displayEditablePicture($picture,
					"Submit",
					"One or more fields are incorrectly specified and marked with a red color!",
					"This field is incorrectly specified!");
			}
		}
		else
		{
			?>
			<p><?php print($error); ?></p>
			<?php
		}
		?>

		<script type="text/javascript">
		sbeditor.initEditors();
		</script>
	</body>
</html>
