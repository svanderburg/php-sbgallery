<?php
use SBGallery\Model\CRUD\AlbumCRUDInterface;

global $route, $currentPage, $checker, $crudInterface;

$checker = $currentPage->constructGalleryPermissionChecker();
$crudInterface = new AlbumCRUDInterface($route, $currentPage, $checker);
$crudInterface->executeOperation();
?>
