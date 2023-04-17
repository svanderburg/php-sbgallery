<?php
namespace Examples\CRUD\Model\Page;
use PDO;
use SBGallery\Model\Page\GalleryPage;
use SBGallery\Model\Page\Content\GalleryContents;
use SBGallery\Model\Page\Settings\GalleryPageSettings;
use SBGallery\Model\GalleryPermissionChecker;
use Examples\CRUD\Model\MyGalleryPermissionChecker;

class MyGalleryPage extends GalleryPage
{
	public function __construct(PDO $dbh)
	{
		parent::__construct($dbh, new GalleryPageSettings("gallery"), new MyGalleryPermissionChecker(), new GalleryContents(null, "contents", "HTML", array("gallery.css")));
	}
}
?>
