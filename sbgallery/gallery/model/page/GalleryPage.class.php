<?php
require_once("crud/model/page/DynamicContentCRUDPage.class.php");
require_once("AlbumPage.class.php");
require_once(dirname(__FILE__)."/../crud/GalleryCRUDModel.class.php");
require_once(dirname(__FILE__)."/../crud/AlbumCRUDModel.class.php");
require_once(dirname(__FILE__)."/../GalleryPermissionChecker.interface.php");
require_once("util/composegallerycontents.inc.php");

abstract class GalleryPage extends DynamicContentCRUDPage
{
	public function __construct(array $sections = null, $gallerySection = "contents")
	{
		$baseURL = Page::computeBaseURL();

		$contentsPath = dirname(__FILE__)."/../../view/html/contents/crud/";
		$htmlEditorJsPath = $baseURL."/lib/sbeditor/editor/scripts/htmleditor.js";

		parent::__construct("Gallery",
			/* Parameter name */
			"albumId",
			/* Key fields */
			array(),
			/* Default contents */
			new Contents(composeGalleryContents($sections, $gallerySection, $contentsPath."gallery.inc.php")),
			/* Error contents */
			new Contents(composeGalleryContents($sections, $gallerySection, $contentsPath."error.inc.php")),
			/* Contents per operation */
			array(
				"create_album" => new Contents(composeGalleryContents($sections, $gallerySection, $contentsPath."album.inc.php"), null, null, array($htmlEditorJsPath)),
				"insert_album" => new Contents(composeGalleryContents($sections, $gallerySection, $contentsPath."album.inc.php"), null, null, array($htmlEditorJsPath))
			),
			new AlbumPage($this, $sections, $gallerySection));
	}

	public function constructCRUDModel()
	{
		$gallery = $this->constructGallery();

		if(array_key_exists("__operation", $_REQUEST))
		{
			switch($_REQUEST["__operation"])
			{
				case "create_album":
				case "insert_album":
					return new AlbumCRUDModel($this, $gallery->constructAlbum());
				default:
					return new GalleryCRUDModel($this, $gallery);
			}
		}
		else
			return new GalleryCRUDModel($this, $gallery);
	}

	public abstract function constructGallery();

	public abstract function constructGalleryPermissionChecker();
}
?>
