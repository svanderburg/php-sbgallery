<?php
namespace Examples\FormEditorPaged\Model;
use PDO;
use SBGallery\Model\Gallery;
use SBGallery\Model\Settings\GallerySettings;
use SBGallery\Model\Settings\URLGenerator\SimpleGalleryURLGenerator;

class MyGallery extends Gallery
{
	public function __construct(PDO $dbh)
	{
		parent::__construct($dbh,
			new GallerySettings(
				new SimpleGalleryURLGenerator(),
				"gallery", /* Base URL */
				"gallery", /* Base dir */
				160, 160, /* Thumbnail size */
				1280, 1280, /* Picture size */
				0666, /* File permissions */
				0777, /* Directory permissions */
				true, /* Display anchors */
				"image/gallery", /* Icons path */
				"album", /* Album anchor prefix */
				"picture", /* Picture anchor prefix */
				null, /* Gallery labels */
				null, /* Album labels */
				null, /* Picture labels */
				null, /* Album editor settings */
				null, /* Picture editor settings */
				10, /* Gallery page size */
				10 /* Album page size */
			)
		);
	}
}
?>
