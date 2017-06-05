<?php
require_once("gallery/view/html/picture.inc.php");

global $crudModel;
$pictureURL = $_SERVER["PHP_SELF"];
$albumURL = dirname($pictureURL);
$galleryURL = dirname($albumURL);

displayPictureBreadcrumbs($crudModel->picture, $galleryURL, $albumURL, $pictureURL, "displayLayoutAlbumOrPictureLink", "displayLayoutAlbumOrPictureLink");

if($crudModel->checker->checkWritePermissions())
{
	displayEditablePicture($crudModel->picture,
		"Submit",
		"One or more fields are incorrectly specified and marked with a red color!",
		"This field is incorrectly specified!",
		"displayLayoutImageLink");
	?>
	<script type="text/javascript">
	sbeditor.initEditors();
	</script>
	<?php
}
else
	displayPicture($crudModel->picture, "displayLayoutImageLink");
?>
