<?php
require_once("AlbumPage.class.php");
require_once("crud/model/page/StaticContentCRUDPage.class.php");
require_once(dirname(__FILE__)."/../crud/PictureCRUDModel.class.php");
require_once(dirname(__FILE__)."/../GalleryPermissionChecker.interface.php");
require_once("util/composegallerycontents.inc.php");

class PicturePage extends StaticContentCRUDPage
{
	private $parent;

	public function __construct(AlbumPage $parent = null, array $sections = null, $gallerySection = "contents")
	{
		$baseURL = Page::computeBaseURL();

		$contentsPath = dirname(__FILE__)."/../../view/html/contents/crud/";
		$htmlEditorJsPath = $baseURL."/lib/sbeditor/editor/scripts/htmleditor.js";

		parent::__construct("Picture",
			/* Key fields */
			array(
				"albumId" => new TextField(true),
				"pictureId" => new TextField(true)
			),
			/* Default contents */
			new Contents(composeGalleryContents($sections, $gallerySection, $contentsPath."picture.inc.php"), null, null, array($htmlEditorJsPath)),
			/* Error contents */
			new Contents(composeGalleryContents($sections, $gallerySection, $contentsPath."error.inc.php")),
			/* Contents per operation */
			array(),
			null);
		
		$this->parent = $parent;
	}

	public function constructCRUDModel()
	{
		return new PictureCRUDModel($this, $this->constructPicture());
	}

	public function constructPicture()
	{
		if($this->parent === null)
			throw new Exception("Can't construct picture!");
		else
		{
			$album = $this->parent->constructAlbum();
			$keyFields = $this->getKeyFields();
			return $album->constructPicture($keyFields["albumId"]->value);
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
