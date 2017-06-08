<?php
set_include_path("./lib/sbdata:./lib/sbgallery:./lib/sbeditor");

require_once("includes/model/MyGallery.class.php");
require_once("gallery/view/html/picturepickerpage.inc.php");

$myGallery = new MyGallery();
displayPicturePickerPage($myGallery);
?>
