<?php
namespace SBGallery\Model\Page;
use Exception;
use SBLayout\Model\Page\Page;
use SBLayout\Model\Page\Content\Contents;
use SBData\Model\Value\Value;
use SBCrud\Model\CRUDModel;
use SBCrud\Model\Page\StaticContentCRUDPage;
use SBGallery\Model\Picture;
use SBGallery\Model\GalleryPermissionChecker;
use SBGallery\Model\CRUD\PictureCRUDModel;

class PicturePage extends StaticContentCRUDPage
{
	private ?AlbumPage $parent;

	public function __construct(AlbumPage $parent = null, array $sections = array(), string $view = "HTML", string $gallerySection = "contents", array $styles = array())
	{
		$baseURL = Page::computeBaseURL();

		$contentsPath = dirname(__FILE__)."/../../View/".$view."/contents/crud/";
		$htmlEditorJsPath = $baseURL."/scripts/htmleditor.js";

		parent::__construct("Picture",
			/* Key values */
			array(
				"albumId" => new Value(true, 255),
				"pictureId" => new Value(true, 255)
			),
			/* Default contents */
			new Contents(\SBGallery\Model\Page\Util\composeGalleryContents($sections, $gallerySection, $contentsPath."picture.php"), null, $styles, array($htmlEditorJsPath)),
			/* Error contents */
			new Contents(\SBGallery\Model\Page\Util\composeGalleryContents($sections, $gallerySection, $contentsPath."error.php"), null, $styles),
			/* Contents per operation */
			array(),
			array());

		$this->parent = $parent;
	}

	public function constructCRUDModel(): CRUDModel
	{
		return new PictureCRUDModel($this, $this->constructPicture());
	}

	public function constructPicture(): Picture
	{
		if($this->parent === null)
			throw new Exception("Can't construct picture!");
		else
		{
			$album = $this->parent->constructAlbum();
			$keyValues = $this->getKeyValues();
			return $album->constructPicture($keyValues["albumId"]->value);
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
