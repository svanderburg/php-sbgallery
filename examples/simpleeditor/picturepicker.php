<?php
require_once("../../vendor/autoload.php");
require_once("../lowlevel/includes/config.php");

use Examples\LowLevel\Model\MyGallery;

$myGallery = new MyGallery($dbh);
\SBGallery\View\HTML\displayPicturePickerPage($myGallery, "Gallery", array("style.css", "gallery.css"));
?>
