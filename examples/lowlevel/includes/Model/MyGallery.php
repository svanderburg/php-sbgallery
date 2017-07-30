<?php
namespace Examples\LowLevel\Model;
use PDO;
use SBGallery\Model\Gallery;

class MyGallery extends Gallery
{
	function __construct()
	{
		$dbh = new PDO("mysql:host=localhost;dbname=gallery", "root", "admin", array(
			PDO::ATTR_PERSISTENT => true
		));

		parent::__construct($dbh, "gallery", "album.php", "picture.php", "multiplepictures.php", "image/gallery", "gallery", 160, 120, 1280, 960);
	}
}
?>
