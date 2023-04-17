<?php
namespace Examples\LowLevel\Model;
use PDO;
use SBGallery\Model\Gallery;
use SBGallery\Model\Settings\GallerySettings;
use SBGallery\Model\Settings\URLGenerator\SimpleGalleryURLGenerator;

class MyGallery extends Gallery
{
	public function __construct(PDO $dbh)
	{
		parent::__construct($dbh, new GallerySettings(new SimpleGalleryURLGenerator(), "gallery"));
	}
}
?>
