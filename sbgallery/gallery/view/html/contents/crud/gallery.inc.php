<?php
require_once("gallery/view/html/gallery.inc.php");

global $crudModel;

displayGalleryBreadcrumbs($_SERVER["PHP_SELF"]);

if($crudModel->checker->checkWritePermissions())
	displayEditableGallery($crudModel->gallery, "displayLayoutAlbumLink", "displayLayoutAddAlbumLink");
else
	displayGallery($crudModel->gallery, "displayLayoutAlbumLink");
?>
