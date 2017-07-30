<?php
require_once("../../vendor/autoload.php");

use Examples\LowLevel\Model\MyGallery;

$myGallery = new MyGallery();
\SBGallery\View\HTML\displayPicturePickerPage($myGallery);
?>
