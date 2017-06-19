<?php
require_once("crud/model/page/DynamicContentCRUDPage.class.php");
require_once("GalleryPage.class.php");
require_once("PicturePage.class.php");
require_once("util/composegallerycontents.inc.php");
require_once(dirname(__FILE__)."/../crud/AlbumCRUDModel.class.php");
require_once(dirname(__FILE__)."/../crud/PictureCRUDModel.class.php");
require_once(dirname(__FILE__)."/../GalleryPermissionChecker.interface.php");

class AlbumPage extends DynamicContentCRUDPage
{
	private $parent;

	public function __construct(GalleryPage $parent = null, array $sections = null, $view = "html", $gallerySection = "contents")
	{
		$baseURL = Page::computeBaseURL();

		$contentsPath = dirname(__FILE__)."/../../view/".$view."/contents/crud/";
		$htmlEditorJsPath = $baseURL."/lib/sbeditor/editor/scripts/htmleditor.js";

		parent::__construct("Album",
			/* Parameter name */
			"pictureId",
			/* Key fields */
			array(
				"albumId" => new TextField(true, 20, 255)
			),
			/* Default contents */
			new Contents(composeGalleryContents($sections, $gallerySection, $contentsPath."album.inc.php"), null, null, array($htmlEditorJsPath)),
			/* Error contents */
			new Contents(composeGalleryContents($sections, $gallerySection, $contentsPath."error.inc.php")),
			/* Contents per operation */
			array(
				"create_picture" => new Contents(composeGalleryContents($sections, $gallerySection, $contentsPath."picture.inc.php"), null, null, array($htmlEditorJsPath)),
				"insert_picture" => new Contents(composeGalleryContents($sections, $gallerySection, $contentsPath."picture.inc.php"), null, null, array($htmlEditorJsPath)),
				"add_multiple_pictures" => new Contents(composeGalleryContents($sections, $gallerySection, $contentsPath."addmultiplepictures.inc.php"))
			),
			new PicturePage($this, $sections, $view, $gallerySection));

		$this->parent = $parent;
	}

	public function constructCRUDModel()
	{
		$album = $this->constructAlbum();

		if(array_key_exists("__operation", $_REQUEST))
		{
			switch($_REQUEST["__operation"])
			{
				case "create_picture":
				case "insert_picture":
					$keyFields = $this->getKeyFields();
					return new PictureCRUDModel($this, $album->constructPicture($keyFields["albumId"]->value));
				default:
					return new AlbumCRUDModel($this, $album);
			}
		}
		else
			return new AlbumCRUDModel($this, $album);
	}

	public function constructAlbum()
	{
		if($this->parent === null)
			throw new Exception("Can't construct an album");
		else
		{
			$gallery = $this->parent->constructGallery();
			return $gallery->constructAlbum();
		}
	}

	public function constructGalleryPermissionChecker()
	{
		if($this->parent === null)
			throw new Exception("Can't construct a permission checker");
		else
			return $this->parent->constructGalleryPermissionChecker();
	}
}
?>
