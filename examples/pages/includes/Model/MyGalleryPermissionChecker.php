<?php
namespace Examples\Pages\Model;
use SBGallery\Model\GalleryPermissionChecker;

class MyGalleryPermissionChecker implements GalleryPermissionChecker
{
	public function checkWritePermissions(): bool
	{
		return (!array_key_exists("view", $_GET) || $_GET["view"] !== "1");
	}
}
?>
