<?php
require_once("gallery/model/page/GalleryPage.class.php");
require_once("MyGallery.class.php");
require_once("MyGalleryPermissionChecker.class.php");

class MyGalleryPage extends GalleryPage
{
	public function constructGallery()
	{
		return new MyGallery();
	}

	public function constructGalleryPermissionChecker()
	{
		return new MyGalleryPermissionChecker();
	}
}
?>
