<?php
namespace Examples\Pages\Model\Page;
use PDO;
use SBGallery\Model\Page\TraversableGalleryPage;
use SBGallery\Model\Page\Content\GalleryContents;
use SBGallery\Model\Page\Settings\GalleryPageSettings;
use Examples\Pages\Model\MyGallery;
use Examples\Pages\Model\MyGalleryPermissionChecker;

class MyGalleryPage extends TraversableGalleryPage
{
	public function __construct(PDO $dbh)
	{
		parent::__construct($dbh, new GalleryPageSettings("gallery"), new MyGalleryPermissionChecker(), new GalleryContents(null, "contents", "HTML", array("gallery.css"), array(), "gallery.php"));
	}
}
?>
