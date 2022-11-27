<?php
global $route, $crudInterface, $checker;

$picture = $crudInterface->picture;

\SBLayout\View\HTML\displayBreadcrumbs($route, 0);

if($checker->checkWritePermissions())
{
	\SBGallery\View\HTML\displayEditablePicture($picture,
		$picture->labels["Submit"],
		$picture->labels["Form invalid"],
		$picture->labels["Field invalid"],
		"Layout");
	?>
	<script type="text/javascript">
	sbeditor.initEditors();
	</script>
	<?php
}
else
	\SBGallery\View\HTML\displayPicture($picture, "Layout");
?>
