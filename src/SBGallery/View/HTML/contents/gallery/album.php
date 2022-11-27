<?php
global $route, $crudInterface, $checker;

$album = $crudInterface->album;

\SBLayout\View\HTML\displayBreadcrumbs($route, 0);

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
