<?php
global $route, $currentPage;

\SBLayout\View\HTML\displayBreadcrumbs($route, 0);

if(array_key_exists("requestParameters", $GLOBALS) && array_key_exists("galleryPage", $GLOBALS["requestParameters"]))
	$page = $GLOBALS["requestParameters"]["galleryPage"];
else
	$page = 0;

if($currentPage->checker->checkWritePermissions())
	\SBGallery\View\HTML\displayEditableGallery($currentPage->gallery, $page);
else
	\SBGallery\View\HTML\displayGallery($currentPage->gallery, $page);
?>
