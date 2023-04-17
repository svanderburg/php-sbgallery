<?php
global $route, $crudInterface, $currentPage;

\SBLayout\View\HTML\displayBreadcrumbs($route, 0);

if($currentPage->checker->checkWritePermissions())
{
	\SBGallery\View\HTML\displayEditableAlbum($crudInterface->album);
	?>
	<script type="text/javascript">
	sbeditor.initEditors();
	</script>
	<?php
}
else
	\SBGallery\View\HTML\displayAlbum($crudInterface->album);
?>
