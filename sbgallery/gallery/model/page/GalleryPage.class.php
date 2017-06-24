<?php
require_once("crud/model/page/DynamicContentCRUDPage.class.php");
require_once("AlbumPage.class.php");
require_once(dirname(__FILE__)."/../crud/GalleryCRUDModel.class.php");
require_once(dirname(__FILE__)."/../crud/AlbumCRUDModel.class.php");
require_once(dirname(__FILE__)."/../GalleryPermissionChecker.interface.php");
require_once("util/composegallerycontents.inc.php");

abstract class GalleryPage extends DynamicContentCRUDPage
{
	/**
	 * Constructs a new gallery page
	 *
	 * @param string $title Title of the gallery page
	 * @param array $sections An array mapping the name of content sections to a PHP file that should be displayed in it
	 * @param string $view The kind of view it should use to display the gallery elements
	 * @param string $gallerySectionContent PHP file that should be displayed when the gallery is opened
	 * @param string $gallerySection The name of the content section that should display the gallery (defaults to: contents)
	 * @param array $styles An array containing stylesheet files to include
	 */
	public function __construct($title, array $sections = null, $view = "html", $gallerySectionContent = null, $gallerySection = "contents", array $styles = null)
	{
		$baseURL = Page::computeBaseURL();

		$contentsPath = dirname(__FILE__)."/../../view/".$view."/contents/crud/";
		$htmlEditorJsPath = $baseURL."/lib/sbeditor/editor/scripts/htmleditor.js";

		if($gallerySectionContent === null)
			$gallerySectionContent = $contentsPath."gallery.inc.php";

		parent::__construct($title,
			/* Parameter name */
			"albumId",
			/* Key fields */
			array(),
			/* Default contents */
			new Contents(composeGalleryContents($sections, $gallerySection, $gallerySectionContent), null, $styles),
			/* Error contents */
			new Contents(composeGalleryContents($sections, $gallerySection, $contentsPath."error.inc.php"), null, $styles),
			/* Contents per operation */
			array(
				"create_album" => new Contents(composeGalleryContents($sections, $gallerySection, $contentsPath."album.inc.php"), null, $styles, array($htmlEditorJsPath)),
				"insert_album" => new Contents(composeGalleryContents($sections, $gallerySection, $contentsPath."album.inc.php"), null, $styles, array($htmlEditorJsPath))
			),
			new AlbumPage($this, $sections, $view, $gallerySection, $styles));
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
