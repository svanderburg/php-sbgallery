<?php
namespace Examples\Pages\Model\Page;
use PDO;
use SBGallery\Model\Gallery;
use SBGallery\Model\GalleryPermissionChecker;
use SBGallery\Model\Page\TraversableGalleryPage;
use SBGallery\Model\Page\Content\GalleryContents;
use Examples\Pages\Model\MyGallery;
use Examples\Pages\Model\MyGalleryPermissionChecker;

class MyGalleryPage extends TraversableGalleryPage
{
	public function __construct(PDO $dbh)
	{
		parent::__construct($dbh, "Gallery", new GalleryContents(null, "contents", "HTML", array("gallery.css"), array(), "gallery.php"));
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
