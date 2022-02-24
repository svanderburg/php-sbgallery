<?php
namespace Examples\Pages\Model\Page;
use PDO;
use SBGallery\Model\Gallery;
use SBGallery\Model\GalleryPermissionChecker;
use SBGallery\Model\Page\GalleryPage;
use Examples\Pages\Model\MyGallery;
use Examples\Pages\Model\MyGalleryPermissionChecker;

class MyGalleryPage extends GalleryPage
{
	private $dbh;

	public function __construct(PDO $dbh)
	{
		parent::__construct("Gallery", array(), "Pages", "gallery.php");
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
