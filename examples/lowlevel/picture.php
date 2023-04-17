<?php
error_reporting(E_STRICT | E_ALL);

require_once(dirname(__FILE__)."/../../vendor/autoload.php");
require_once("includes/config.php");

use SBData\Model\Table\Anchor\AnchorRow;
use SBGallery\Model\Picture;
use SBGallery\Model\Exception\AlbumNotFoundException;
use SBGallery\Model\Exception\PictureNotFoundException;
use Examples\LowLevel\Model\MyGallery;

$myGallery = new MyGallery($dbh);

$error = null;

try
{
	if(!array_key_exists("ALBUM_ID", $_REQUEST))
		throw new Exception("No ALBUM_ID specified!");

	$album = $myGallery->queryAlbum($_REQUEST["ALBUM_ID"]);

	if(array_key_exists($myGallery->settings->operationParam, $_REQUEST))
	{
		$operationParam = $_REQUEST[$myGallery->settings->operationParam];

		if($operationParam == "insert_picture")
		{
			$picture = $album->newPicture();
			$picture->importValues($_REQUEST);
			$picture->checkFields();

			if($picture->checkValid())
			{
				$album->insertPicture($picture);

				header("Location: picture.php?".http_build_query(array(
					"ALBUM_ID" => $picture->form->fields["ALBUM_ID"]->exportValue(),
					"PICTURE_ID" => $picture->form->fields["PICTURE_ID"]->exportValue()
				), "", null, PHP_QUERY_RFC3986));
				exit();
			}
		}
		else if($operationParam == "update_picture")
		{
			$picture = $album->queryPicture($_GET["PICTURE_ID"]);
			$picture->importValues($_POST);
			$picture->checkFields();

			if($picture->checkValid())
			{
				$album->updatePicture($_GET["PICTURE_ID"], $picture);

				header("Location: picture.php?".http_build_query(array(
					"ALBUM_ID" => $picture->form->fields["ALBUM_ID"]->exportValue(),
					"PICTURE_ID" => $picture->form->fields["PICTURE_ID"]->exportValue()
				), "", null, PHP_QUERY_RFC3986));
				exit();
			}
		}
		else if($operationParam == "remove_picture")
		{
			$album->removePicture($_REQUEST["PICTURE_ID"]);

			header("Location: album.php?".http_build_query(array(
				"ALBUM_ID" => $_REQUEST["ALBUM_ID"]
			), "", null, PHP_QUERY_RFC3986).AnchorRow::composeRowFragment($album->settings->anchorPrefix));
			exit();
		}
		else if($operationParam == "moveleft_picture")
		{
			if($album->moveLeftPicture($_REQUEST["PICTURE_ID"]))
				$rowFragment = AnchorRow::composePreviousRowFragment($album->settings->anchorPrefix);
			else
				$rowFragment = AnchorRow::composeRowFragment($album->settings->anchorPrefix);

			header("Location: album.php?".http_build_query(array(
				"ALBUM_ID" => $_REQUEST["ALBUM_ID"]
			), "", null, PHP_QUERY_RFC3986).$rowFragment);
			exit();
		}
		else if($operationParam == "moveright_picture")
		{
			if($album->moveRightPicture($_REQUEST["PICTURE_ID"]))
				$rowFragment = AnchorRow::composeNextRowFragment($album->settings->anchorPrefix);
			else
				$rowFragment = AnchorRow::composeRowFragment($album->settings->anchorPrefix);

			header("Location: album.php?".http_build_query(array(
				"ALBUM_ID" => $_REQUEST["ALBUM_ID"]
			), "", null, PHP_QUERY_RFC3986).$rowFragment);
			exit();
		}
		else if($operationParam == "setasthumbnail_picture")
		{
			$album->setAsThumbnail($_REQUEST["PICTURE_ID"]);

			header("Location: album.php?".http_build_query(array(
				"ALBUM_ID" => $_REQUEST["ALBUM_ID"]
			), "", null, PHP_QUERY_RFC3986).AnchorRow::composeRowFragment($album->settings->anchorPrefix));
			exit();
		}
		else if($operationParam == "clear_picture")
		{
			$album->clearPicture($_REQUEST["PICTURE_ID"]);

			header("Location: picture.php?".http_build_query(array(
				"ALBUM_ID" => $_REQUEST["ALBUM_ID"],
				"PICTURE_ID" => $_REQUEST["PICTURE_ID"]
			), "", null, PHP_QUERY_RFC3986));
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
		if(array_key_exists("PICTURE_ID", $_REQUEST))
			$picture = $album->queryPicture($_REQUEST["PICTURE_ID"]);
		else
			$picture = $album->newPicture();
	}
}
catch(AlbumNotFoundException $ex)
{
	header("HTTP/1.1 404 Not Found");
	$error = $ex->getMessage();
}
catch(PictureNotFoundException $ex)
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
		<title>Picture</title>
		<link rel="stylesheet" type="text/css" href="style.css">
		<link rel="stylesheet" type="text/css" href="gallery.css">
		<script type="text/javascript" src="scripts/htmleditor.js"></script>
	</head>
	<body>
		<h1>Picture</h1>
		<?php
		if($error === null)
		{
			if(array_key_exists("view", $_GET) && $_GET["view"] == "1")
				\SBGallery\View\HTML\displayPicture($picture);
			else
				\SBGallery\View\HTML\displayEditablePicture($picture);
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
