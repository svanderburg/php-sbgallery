<?php
global $route, $currentPage;

\SBLayout\View\HTML\displayBreadcrumbs($route, 0);

$checker = $currentPage->constructGalleryPermissionChecker();

if($checker->checkWritePermissions())
	\SBGallery\View\HTML\displayEditableGallery($currentPage->gallery, "Layout");
else
	\SBGallery\View\HTML\displayGallery($currentPage->gallery, "Layout");
?>
