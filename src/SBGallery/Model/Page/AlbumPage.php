<?php
namespace SBGallery\Model\Page;
use SBLayout\Model\PageNotFoundException;
use SBLayout\Model\Page\ContentPage;
use SBData\Model\Value\Value;
use SBData\Model\Value\AcceptableFileNameValue;
use SBCrud\Model\Page\CRUDMasterPage;
use SBCrud\Model\Page\OperationPage;
use SBGallery\Model\Gallery;
use SBGallery\Model\GalleryPermissionChecker;
use SBGallery\Model\Album;
use SBGallery\Model\Page\Content\AlbumContents;
use SBGallery\Model\Page\Content\PictureContents;

class AlbumPage extends CRUDMasterPage
{
	public GalleryPage $galleryPage;

	private PictureContents $pictureContents;

	public string $albumId;

	public Album $album;

	public function __construct(GalleryPage $galleryPage, string $albumId, string $title = "Album", AlbumContents $contents = null)
	{
		if($contents === null)
			$contents = new AlbumContents();

		$pictureContents = $contents->constructPictureContents();

		$this->album = $galleryPage->gallery->constructAlbum();

		parent::__construct($title, "pictureId", $contents, array(
			"create_picture" => new GalleryOperationPage($this, $this->album->albumLabels["Add picture"], $pictureContents),
			"insert_picture" => new GalleryOperationPage($this, $this->album->albumLabels["Insert picture"], $pictureContents),
			"update_album" => new GalleryOperationPage($this, $this->album->albumLabels["Update album"], $contents),
			"remove_album" => new GalleryOperationPage($this, $this->album->albumLabels["Remove album"], $contents),
			"moveleft_album" => new GalleryOperationPage($this, $this->album->albumLabels["Move left"], $contents),
			"moveright_album" => new GalleryOperationPage($this, $this->album->albumLabels["Move right"], $contents),
			"add_multiple_pictures" => new OperationPage($this->album->albumLabels["Add multiple pictures"], $contents->constructMultiplePictureContents()),
			"insert_multiple_pictures" => new GalleryOperationPage($this, $this->album->albumLabels["Insert multiple pictures"], $contents)
		), "Invalid query parameter:", "Invalid operation:", "__operation", dirname(__FILE__)."/../../View/HTML/menuitems/album.php");

		$this->galleryPage = $galleryPage;
		$this->albumId = $albumId;
		$this->pictureContents = $pictureContents;

		$this->album->fetchEntity($albumId);
		if($this->album->entity === false)
			throw new PageNotFoundException($this->album->albumLabels["Cannot find album:"]." ".$albumId);
		else
			$this->title = $this->album->entity["Title"];
	}

	public function createParamValue(): Value
	{
		return new AcceptableFileNameValue(true, 255);
	}

	public function createDetailPage(array $query): ?ContentPage
	{
		return new PicturePage($this, $this->album, $query["pictureId"], $this->albumId, "Picture", $this->pictureContents);
	}

	public function constructGalleryPermissionChecker(): GalleryPermissionChecker
	{
		return $this->galleryPage->constructGalleryPermissionChecker();
	}
}
?>
