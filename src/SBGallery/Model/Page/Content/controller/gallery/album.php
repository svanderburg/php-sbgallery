<?php
use SBGallery\Model\CRUD\AlbumCRUDInterface;

global $route, $currentPage, $crudInterface;

$crudInterface = new AlbumCRUDInterface($route, $currentPage, $currentPage->checker);
$crudInterface->executeOperation();
?>
