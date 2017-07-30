<?php
namespace Examples\Pages\Model\Page;
use PDO;
use SBGallery\Model\Page\GalleryPage;
use Examples\Pages\Model\MyGallery;
use Examples\Pages\Model\MyGalleryPermissionChecker;

class MyGalleryPage extends GalleryPage
{
	private $dbh;

	public function __construct(PDO $dbh)
	{
		parent::__construct("Gallery", null, "Pages", "gallery.php");
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
