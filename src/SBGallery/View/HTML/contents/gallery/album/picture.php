<?php
global $route, $currentPage, $crudInterface;

\SBLayout\View\HTML\displayBreadcrumbs($route, 0);

if($currentPage->checker->checkWritePermissions())
{
	\SBGallery\View\HTML\displayEditablePicture($crudInterface->picture);
	?>
	<script type="text/javascript">
	sbeditor.initEditors();
	</script>
	<?php
}
else
	\SBGallery\View\HTML\displayPicture($crudInterface->picture);
?>
