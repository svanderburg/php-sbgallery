<?php
namespace SBGallery\Model\Page;
use Exception;
use SBLayout\Model\Page\Page;
use SBLayout\Model\Page\Content\Contents;
use SBCrud\Model\CRUDModel;
use SBCrud\Model\Page\DynamicContentCRUDPage;
use SBData\Model\Field\TextField;
use SBGallery\Model\Album;
use SBGallery\Model\GalleryPermissionChecker;
use SBGallery\Model\CRUD\AlbumCRUDModel;
use SBGallery\Model\CRUD\PictureCRUDModel;

class AlbumPage extends DynamicContentCRUDPage
{
	private ?GalleryPage $parent;

	public function __construct(GalleryPage $parent = null, array $sections = array(), string $view = "HTML", string $gallerySection = "contents", array $styles = array())
	{
		$baseURL = Page::computeBaseURL();

		$contentsPath = dirname(__FILE__)."/../../View/".$view."/contents/crud/";
		$htmlEditorJsPath = $baseURL."/scripts/htmleditor.js";

		parent::__construct("Album",
			/* Parameter name */
			"pictureId",
			/* Key fields */
			array(
				"albumId" => new TextField(true, 20, 255)
			),
			/* Default contents */
			new Contents(\SBGallery\Model\Page\Util\composeGalleryContents($sections, $gallerySection, $contentsPath."album.php"), null, $styles, array($htmlEditorJsPath)),
			/* Error contents */
			new Contents(\SBGallery\Model\Page\Util\composeGalleryContents($sections, $gallerySection, $contentsPath."error.php"), null, $styles),
			/* Contents per operation */
			array(
				"create_picture" => new Contents(\SBGallery\Model\Page\Util\composeGalleryContents($sections, $gallerySection, $contentsPath."picture.php"), null, $styles, array($htmlEditorJsPath)),
				"insert_picture" => new Contents(\SBGallery\Model\Page\Util\composeGalleryContents($sections, $gallerySection, $contentsPath."picture.php"), null, $styles, array($htmlEditorJsPath)),
				"add_multiple_pictures" => new Contents(\SBGallery\Model\Page\Util\composeGalleryContents($sections, $gallerySection, $contentsPath."addmultiplepictures.php"), null, $styles)
			),
			new PicturePage($this, $sections, $view, $gallerySection, $styles));

		$this->parent = $parent;
	}

	public function constructCRUDModel(): CRUDModel
	{
		$album = $this->constructAlbum();

		if(array_key_exists("__operation", $_REQUEST))
		{
			switch($_REQUEST["__operation"])
			{
				case "create_picture":
				case "insert_picture":
					$keyFields = $this->getKeyFields();
					return new PictureCRUDModel($this, $album->constructPicture($keyFields["albumId"]->exportValue()));
				default:
					return new AlbumCRUDModel($this, $album);
			}
		}
		else
			return new AlbumCRUDModel($this, $album);
	}

	public function constructAlbum(): Album
	{
		if($this->parent === null)
			throw new Exception("Can't construct an album");
		else
		{
			$gallery = $this->parent->constructGallery();
			return $gallery->constructAlbum();
		}
	}

	public function constructGalleryPermissionChecker(): GalleryPermissionChecker
	{
		if($this->parent === null)
			throw new Exception("Can't construct a permission checker");
		else
			return $this->parent->constructGalleryPermissionChecker();
	}
}
?>
