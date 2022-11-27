<?php
use SBGallery\Model\CRUD\PictureCRUDInterface;

global $route, $currentPage, $checker, $crudInterface;

$checker = $currentPage->constructGalleryPermissionChecker();
$crudInterface = new PictureCRUDInterface($route, $currentPage, $checker);
$crudInterface->executeOperation();
?>
