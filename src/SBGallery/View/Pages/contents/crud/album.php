<?php
global $crudModel;

if($crudModel->checker->checkWritePermissions())
{
	\SBGallery\View\HTML\displayEditableAlbum($crudModel->album,
		$crudModel->album->albumLabels["Submit"],
		$crudModel->album->albumLabels["Form invalid"],
		$crudModel->album->albumLabels["Field invalid"],
		"Layout");
	?>
	<script type="text/javascript">
	sbeditor.initEditors();
	</script>
	<?php
}
else
	\SBGallery\View\HTML\displayAlbum($crudModel->album, "Layout");
?>
