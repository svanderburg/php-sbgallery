<?php
namespace SBGallery\Model\Page;
use SBLayout\Model\Page\Page;
use SBLayout\Model\Page\Content\Contents;
use SBCrud\Model\CRUDModel;
use SBCrud\Model\Page\DynamicContentCRUDPage;
use SBGallery\Model\Gallery;
use SBGallery\Model\GalleryPermissionChecker;
use SBGallery\Model\CRUD\AlbumCRUDModel;
use SBGallery\Model\CRUD\GalleryCRUDModel;

abstract class GalleryPage extends DynamicContentCRUDPage
{
	/**
	 * Constructs a new gallery page
	 *
	 * @param $title Title of the gallery page
	 * @param $sections An array mapping the name of content sections to a PHP file that should be displayed in it
	 * @param $view The kind of view it should use to display the gallery elements
	 * @param $gallerySectionContent PHP file that should be displayed when the gallery is opened
	 * @param $gallerySection The name of the content section that should display the gallery (defaults to: contents)
	 * @param $styles An array containing stylesheet files to include
	 */
	public function __construct(string $title, array $sections = array(), string $view = "HTML", string $gallerySectionContent = null, string $gallerySection = "contents", array $styles = array())
	{
		$baseURL = Page::computeBaseURL();

		$contentsPath = dirname(__FILE__)."/../../View/".$view."/contents/crud/";
		$htmlEditorJsPath = $baseURL."/scripts/htmleditor.js";

		if($gallerySectionContent === null)
			$gallerySectionContent = $contentsPath."gallery.php";

		parent::__construct($title,
			/* Parameter name */
			"albumId",
			/* Key values */
			array(),
			/* Default contents */
			new Contents(\SBGallery\Model\Page\Util\composeGalleryContents($sections, $gallerySection, $gallerySectionContent), null, $styles),
			/* Error contents */
			new Contents(\SBGallery\Model\Page\Util\composeGalleryContents($sections, $gallerySection, $contentsPath."error.php"), null, $styles),
			/* Contents per operation */
			array(
				"create_album" => new Contents(\SBGallery\Model\Page\Util\composeGalleryContents($sections, $gallerySection, $contentsPath."album.php"), null, $styles, array($htmlEditorJsPath)),
				"insert_album" => new Contents(\SBGallery\Model\Page\Util\composeGalleryContents($sections, $gallerySection, $contentsPath."album.php"), null, $styles, array($htmlEditorJsPath))
			),
			new AlbumPage($this, $sections, $view, $gallerySection, $styles));
	}

	public function constructCRUDModel(): CRUDModel
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

	public abstract function constructGallery(): Gallery;

	public abstract function constructGalleryPermissionChecker(): GalleryPermissionChecker;
}
?>
