<?php
namespace Examples\CRUD\Model\Page;
use PDO;
use SBGallery\Model\Gallery;
use SBGallery\Model\Page\GalleryPage;
use SBGallery\Model\GalleryPermissionChecker;
use Examples\CRUD\Model\MyGallery;
use Examples\CRUD\Model\MyGalleryPermissionChecker;

class MyGalleryPage extends GalleryPage
{
	private PDO $dbh;

	public function __construct(PDO $dbh)
	{
		parent::__construct("Gallery", array(), "HTML", null, "contents", array("gallery.css"));
		$this->dbh = $dbh;
	}

	public function constructGallery(): Gallery
	{
		return new MyGallery($this->dbh);
	}

	public function constructGalleryPermissionChecker(): GalleryPermissionChecker
	{
		return new MyGalleryPermissionChecker();
	}
}
?>
