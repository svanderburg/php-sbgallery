<?php
namespace Examples\CRUD\Model\Page;
use PDO;
use SBGallery\Model\Gallery;
use SBGallery\Model\Page\Content\GalleryContents;
use SBGallery\Model\Page\GalleryPage;
use SBGallery\Model\GalleryPermissionChecker;
use Examples\CRUD\Model\MyGallery;
use Examples\CRUD\Model\MyGalleryPermissionChecker;

class MyGalleryPage extends GalleryPage
{
	public function __construct(PDO $dbh)
	{
		parent::__construct($dbh, "Gallery", new GalleryContents(array(), "contents", "HTML", array("gallery.css")));
	}

	public function constructGallery(PDO $dbh): Gallery
	{
		return new MyGallery($dbh);
	}

	public function constructGalleryPermissionChecker(): GalleryPermissionChecker
	{
		return new MyGalleryPermissionChecker();
	}
}
?>
