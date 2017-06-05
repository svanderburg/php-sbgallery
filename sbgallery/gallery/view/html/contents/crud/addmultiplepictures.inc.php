<?php
require_once("gallery/view/html/multiplepictures.inc.php");

global $crudModel;

displayPicturesUploader($crudModel->keyFields["albumId"]->value);
?>
