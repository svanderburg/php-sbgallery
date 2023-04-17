<?php
global $currentPage;

$album = $currentPage->gallery->queryAlbum($GLOBALS["query"]["albumId"]);
$picturesUploader = $album->constructPicturesUploader();

\SBGallery\View\HTML\displayPicturesUploader($picturesUploader);
?>
