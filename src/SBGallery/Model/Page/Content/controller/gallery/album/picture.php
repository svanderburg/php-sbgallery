<?php
use SBGallery\Model\CRUD\PictureCRUDInterface;

global $route, $currentPage, $crudInterface;

$crudInterface = new PictureCRUDInterface($route, $currentPage);
$crudInterface->executeOperation();
?>
