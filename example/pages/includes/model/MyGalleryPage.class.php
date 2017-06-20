<?php
require_once("gallery/model/page/GalleryPage.class.php");
require_once("MyGallery.class.php");
require_once("MyGalleryPermissionChecker.class.php");

class MyGalleryPage extends GalleryPage
{
	private $dbh;

	public function __construct(PDO $dbh)
	{
		parent::__construct("Gallery", null, "pages", "gallery.inc.php");
		$this->dbh = $dbh;
	}

	public function constructGallery()
	{
		return new MyGallery($this->dbh);
	}

	public function constructGalleryPermissionChecker()
	{
		return new MyGalleryPermissionChecker();
	}
}
?>
