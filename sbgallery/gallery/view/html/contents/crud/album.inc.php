<?php
require_once("gallery/view/html/album.inc.php");

global $crudModel;

$albumURL = $_SERVER["PHP_SELF"];

if($crudModel->album->entity === false)
	$galleryURL = $_SERVER["PHP_SELF"];
else
	$galleryURL = dirname($albumURL);

displayAlbumBreadcrumbs($crudModel->album, $galleryURL, $albumURL, "displayLayoutAlbumLink");

if($crudModel->checker->checkWritePermissions())
{
	displayEditableAlbum($crudModel->album,
		"Submit",
		"One or more fields are incorrectly specified and marked with a red color!",
		"This field is incorrectly specified!",
		"displayLayoutPictureLink",
		"displayLayoutAddPictureLink",
		"displayLayoutAddMultiplePicturesLink");
	?>
	<script type="text/javascript">
	sbeditor.initEditors();
	</script>
	<?php
}
else
	displayAlbum($crudModel->album, "displayLayoutPictureLink");
?>
