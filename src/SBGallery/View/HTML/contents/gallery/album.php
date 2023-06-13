<?php
global $route, $crudInterface, $currentPage;

\SBLayout\View\HTML\displayBreadcrumbs($route, 0);

if(array_key_exists("requestParameters", $GLOBALS) && array_key_exists("albumPage", $GLOBALS["requestParameters"]))
	$page = $GLOBALS["requestParameters"]["albumPage"];
else
	$page = 0;

if($currentPage->checker->checkWritePermissions())
{
	\SBGallery\View\HTML\displayEditableAlbum($crudInterface->album, $page);
	?>
	<script type="text/javascript">
	sbeditor.initEditors();
	</script>
	<?php
}
else
	\SBGallery\View\HTML\displayAlbum($crudInterface->album, $page);
?>
