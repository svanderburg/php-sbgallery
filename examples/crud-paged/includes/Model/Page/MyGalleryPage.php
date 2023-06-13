<?php
namespace Examples\CRUDPaged\Model\Page;
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
		parent::__construct($dbh, new GalleryPageSettings("gallery", /* Base URL */
			"gallery", /* Base dir */
			160, 160, /* Thumbnail size */
			1280, 1280, /* Picture size */
			0666, /* File permissions */
			0777, /* Directory permissions */
			true, /* Display anchors */
			"image/gallery", /* Icons path */
			"album", /* Album anchor prefix */
			"picture", /* Picture anchor prefix */
			null, /* Gallery page labels */
			null, /* Album page labels */
			null, /* Picture page labels */
			null, /* Gallery labels */
			null, /* Album labels */
			null, /* Picture labels */
			null, /* Album editor settings */
			null, /* Picture editor settings */
			null, /* Album editor labels file */
			null, /* Picture editor labels file */
			10, /* Gallery page size */
			10 /* Album page size */
		), new MyGalleryPermissionChecker(), new GalleryContents(null, "contents", "HTML", array("gallery.css")));
	}
}
?>
