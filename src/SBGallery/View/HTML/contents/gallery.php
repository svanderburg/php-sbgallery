<?php
global $route, $currentPage;

\SBLayout\View\HTML\displayBreadcrumbs($route, 0);

if($currentPage->checker->checkWritePermissions())
	\SBGallery\View\HTML\displayEditableGallery($currentPage->gallery);
else
	\SBGallery\View\HTML\displayGallery($currentPage->gallery);
?>
