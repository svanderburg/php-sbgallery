<?php
require_once("gallery/model/Gallery.class.php");

class MyGallery extends Gallery
{
	function __construct()
	{
		$dbh = new PDO("mysql:host=localhost;dbname=gallery", "root", "admin", array(
			PDO::ATTR_PERSISTENT => true
		));

		$baseURL = Page::computeBaseURL();

		$editorSettings = array(
			"id" => "editor1",
			"iframePage" => $baseURL."/iframepage.html",
			"iconsPath" => $baseURL."/lib/sbeditor/editor/image"
		);

		parent::__construct($dbh, $baseURL."/gallery", $baseURL."/index.php/gallery", $baseURL."/picture.php", $baseURL."/multiplepictures.php", $baseURL."/lib/sbgallery/gallery/image", "gallery", 160, 120, 1280, 960, null, null, null, $editorSettings);
	}
}
?>
