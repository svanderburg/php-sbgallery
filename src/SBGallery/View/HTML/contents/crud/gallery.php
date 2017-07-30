<?php
global $crudModel;

\SBGallery\View\HTML\displayGalleryBreadcrumbs($crudModel->gallery, $_SERVER["PHP_SELF"]);

if($crudModel->checker->checkWritePermissions())
	\SBGallery\View\HTML\displayEditableGallery($crudModel->gallery, "Layout");
else
	\SBGallery\View\HTML\displayGallery($crudModel->gallery, "Layout");
?>
