<?php
error_reporting(E_STRICT | E_ALL);

require_once(dirname(__FILE__)."/../../vendor/autoload.php");

use SBData\Model\Table\Anchor\AnchorRow;
use Examples\LowLevel\Model\MyGallery;

/* Construct album model from the gallery model */
$myGallery = new MyGallery();
$album = $myGallery->constructAlbum();

/* Album controller */
try
{
	if(!array_key_exists("ALBUM_ID", $_REQUEST))
		$album->create();
	else if(array_key_exists("__operation", $_REQUEST))
	{
		if($_REQUEST["__operation"] == "insert_album")
		{
			if($album->insert($_REQUEST))
			{
				header("Location: ".$_SERVER["PHP_SELF"]."?ALBUM_ID=".$album->entity["ALBUM_ID"]);
				exit;
			}
		}
		else if($_REQUEST["__operation"] == "update_album")
		{
			if($album->update($_REQUEST))
			{
				header("Location: ".$_SERVER["PHP_SELF"]."?ALBUM_ID=".$album->entity["ALBUM_ID"]);
				exit;
			}
		}
		else if($_REQUEST["__operation"] == "remove_album")
		{
			$album->remove($_REQUEST["ALBUM_ID"]);

			header("Location: ".$_SERVER["HTTP_REFERER"].AnchorRow::composeRowFragment("album"));
			exit;
		}
		else if($_REQUEST["__operation"] == "moveleft_album")
		{
			$album->moveLeft($_REQUEST["ALBUM_ID"]);
			header("Location: ".$_SERVER["HTTP_REFERER"].AnchorRow::composeRowFragment("album"));
			exit;
		}
		else if($_REQUEST["__operation"] == "moveright_album")
		{
			$album->moveRight($_REQUEST["ALBUM_ID"]);
			header("Location: ".$_SERVER["HTTP_REFERER"].AnchorRow::composeRowFragment("album"));
			exit;
		}
		else
			throw new Exception("Unknown operation: ".$_REQUEST["__operation"]);
	}
	else
		$album->view($_REQUEST["ALBUM_ID"]);

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
		<title>Album</title>
		<link rel="stylesheet" type="text/css" href="style.css">
		<script type="text/javascript" src="scripts/htmleditor.js"></script>
	</head>
	<body>
		<h1>Album</h1>
		<?php
		/* View the album */

		if($error === null)
		{
			\SBGallery\View\HTML\displayAlbumBreadcrumbs($album, "gallery.php", "album.php");

			if(array_key_exists("view", $_GET) && $_GET["view"] == "1")
				\SBGallery\View\HTML\displayAlbum($album);
			else
			{
				\SBGallery\View\HTML\displayEditableAlbum($album,
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
