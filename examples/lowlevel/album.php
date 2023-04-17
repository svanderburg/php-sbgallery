<?php
error_reporting(E_STRICT | E_ALL);

require_once(dirname(__FILE__)."/../../vendor/autoload.php");
require_once("includes/config.php");

use SBData\Model\Table\Anchor\AnchorRow;
use SBGallery\Model\Album;
use SBGallery\Model\Exception\AlbumNotFoundException;
use Examples\LowLevel\Model\MyGallery;

$myGallery = new MyGallery($dbh);

$error = null;

try
{
	if(array_key_exists($myGallery->settings->operationParam, $_REQUEST))
	{
		$operationParam = $_REQUEST[$myGallery->settings->operationParam];

		if($operationParam == "insert_album")
		{
			$album = $myGallery->newAlbum();
			$album->importValues($_REQUEST);
			$album->checkFields();

			if($album->checkValid())
			{
				$myGallery->insertAlbum($album);
				header("Location: album.php?ALBUM_ID=".rawurlencode($_REQUEST["ALBUM_ID"]));
				exit();
			}
		}
		else if($operationParam == "update_album")
		{
			$album = $myGallery->newAlbum($_GET["ALBUM_ID"]);
			$album->importValues($_POST);
			$album->checkFields();

			if($album->checkValid())
			{
				$myGallery->updateAlbum($_GET["ALBUM_ID"], $album);
				header("Location: album.php?ALBUM_ID=".rawurlencode($_REQUEST["ALBUM_ID"]));
				exit();
			}
		}
		else if($operationParam == "remove_album")
		{
			$myGallery->removeAlbum($_REQUEST["ALBUM_ID"]);
			header("Location: gallery.php".AnchorRow::composePreviousRowFragment($myGallery->settings->albumAnchorPrefix));
			exit();
		}
		else if($operationParam == "moveleft_album")
		{
			if($myGallery->moveLeftAlbum($_REQUEST["ALBUM_ID"]))
				$rowFragment = AnchorRow::composePreviousRowFragment($myGallery->settings->albumAnchorPrefix);
			else
				$rowFragment = AnchorRow::composeRowFragment($myGallery->settings->albumAnchorPrefix);

			header("Location: gallery.php".$rowFragment);
			exit();
		}
		else if($operationParam == "moveright_album")
		{
			if($myGallery->moveRightAlbum($_REQUEST["ALBUM_ID"]))
				$rowFragment = AnchorRow::composeNextRowFragment($myGallery->settings->albumAnchorPrefix);
			else
				$rowFragment = AnchorRow::composeRowFragment($myGallery->settings->albumAnchorPrefix);

			header("Location: gallery.php".$rowFragment);
			exit();
		}
		else
		{
			header("HTTP/1.1 400 Bad Request");
			$error = "Unknown operation: ".$operationParam;
		}
	}
	else
	{
		if(array_key_exists("ALBUM_ID", $_REQUEST))
			$album = $myGallery->queryAlbum($_REQUEST["ALBUM_ID"]);
		else
			$album = $myGallery->newAlbum();
	}
}
catch(AlbumNotFoundException $ex)
{
	header("HTTP/1.1 404 Not Found");
	$error = $ex->getMessage();
}
catch(Exception $ex)
{
	$error = $ex->getMessage();
}
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Album</title>
		<link rel="stylesheet" type="text/css" href="style.css">
		<link rel="stylesheet" type="text/css" href="gallery.css">
		<script type="text/javascript" src="scripts/htmleditor.js"></script>
	</head>
	<body>
		<h1>Album</h1>
		<?php
		if($error === null)
		{
			if(array_key_exists("view", $_GET) && $_GET["view"] == "1")
				\SBGallery\View\HTML\displayAlbum($album);
			else
				\SBGallery\View\HTML\displayEditableAlbum($album);
		}
		else
		{
			?>
			<p><?= $error ?></p>
			<?php
		}
		?>

		<script type="text/javascript">
		sbeditor.initEditors();
		</script>
	</body>
</html>
