<?php
global $crudInterface, $checker;

$album = $crudInterface->album;

if($checker->checkWritePermissions())
{
	\SBGallery\View\HTML\displayEditableAlbum($album,
		$album->albumLabels["Submit"],
		$album->albumLabels["Form invalid"],
		$album->albumLabels["Field invalid"],
		"Layout");
	?>
	<script type="text/javascript">
	sbeditor.initEditors();
	</script>
	<?php
}
else
	\SBGallery\View\HTML\displayAlbum($album, "Layout");
?>
