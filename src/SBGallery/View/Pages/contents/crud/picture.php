<?php
global $crudModel;

if($crudModel->checker->checkWritePermissions())
{
	\SBGallery\View\HTML\displayEditablePicture($crudModel->picture,
		"Submit",
		"One or more fields are incorrectly specified and marked with a red color!",
		"This field is incorrectly specified!",
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
