<?php
require_once("gallery/model/Gallery.class.php");

class MyGallery extends Gallery
{
	function __construct(PDO $dbh)
	{
		$baseURL = Page::computeBaseURL();

		$editorSettings = array(
			"id" => "editor1",
			"iframePage" => $baseURL."/iframepage.html",
			"iconsPath" => $baseURL."/lib/sbeditor/editor/image",
			"width" => 60,
			"height" => 20
		);

		parent::__construct($dbh, $baseURL."/gallery", $baseURL."/index.php/gallery", $baseURL."/picture.php", $baseURL."/multiplepictures.php", $baseURL."/lib/sbgallery/gallery/image", "gallery", 160, 120, 1280, 960, null, null, null, $editorSettings);
	}
}
?>
