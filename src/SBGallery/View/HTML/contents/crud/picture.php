<?php
global $crudModel;
$pictureURL = $_SERVER["PHP_SELF"];
$albumURL = dirname($pictureURL);

if($crudModel->picture->entity === false)
	$galleryURL = $albumURL;
else
	$galleryURL = dirname($albumURL);

\SBGallery\View\HTML\displayPictureBreadcrumbs($crudModel->picture, $galleryURL, $albumURL, $pictureURL, "Layout");

if($crudModel->checker->checkWritePermissions())
{
	\SBGallery\View\HTML\displayEditablePicture($crudModel->picture,
		$crudModel->picture->labels["Submit"],
		$crudModel->picture->labels["Form invalid"],
		$crudModel->picture->labels["Field invalid"],
		"Layout");
	?>
	<script type="text/javascript">
	sbeditor.initEditors();
	</script>
	<?php
}
else
	\SBGallery\View\HTML\displayPicture($crudModel->picture, "Layout");
?>
