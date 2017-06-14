<?php
require_once("gallery/view/pages/displayalbummenusection.inc.php");

if(visitedGallerySubPage())
	displayAlbumMenuSection($GLOBALS["galleryPage"]);
?>
