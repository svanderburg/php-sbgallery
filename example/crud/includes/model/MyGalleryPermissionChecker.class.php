<?php
require_once("gallery/model/GalleryPermissionChecker.interface.php");

class MyGalleryPermissionChecker implements GalleryPermissionChecker
{
	public function checkWritePermissions()
	{
		return (!array_key_exists("view", $_GET) || $_GET["view"] !== "1");
	}
}
?>
