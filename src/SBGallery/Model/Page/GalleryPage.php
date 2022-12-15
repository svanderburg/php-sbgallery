<?php
namespace SBGallery\Model\Page;
use PDO;
use SBLayout\Model\Page\ContentPage;
use SBData\Model\Value\Value;
use SBData\Model\Value\AcceptableFileNameValue;
use SBCrud\Model\Page\CRUDMasterPage;
use SBCrud\Model\Page\OperationPage;
use SBGallery\Model\Gallery;
use SBGallery\Model\GalleryPermissionChecker;
use SBGallery\Model\Page\Content\GalleryContents;
use SBGallery\Model\Page\Content\AlbumContents;

abstract class GalleryPage extends CRUDMasterPage
{
	public PDO $dbh;

	private AlbumContents $albumContents;

	public Gallery $gallery;

	public function __construct(PDO $dbh, string $title = "Gallery", GalleryContents $contents = null)
	{
		if($contents === null)
			$contents = new GalleryContents();

		$albumContents = $contents->constructAlbumContents($dbh);

		$this->gallery = $this->constructGallery($dbh);

		parent::__construct($title, "albumId", $contents, array(
			"create_album" => new GalleryOperationPage($this, $this->gallery->galleryLabels["Add album"], $albumContents),
			"insert_album" => new GalleryOperationPage($this, $this->gallery->galleryLabels["Insert album"], $albumContents)
		));
		$this->dbh = $dbh;
		$this->albumContents = $albumContents;
	}

	public function createParamValue(): Value
	{
		return new AcceptableFileNameValue(true, 255);
	}

	public function createDetailPage(array $query): ?ContentPage
	{
		return new AlbumPage($this, $query["albumId"], "Album", $this->albumContents);
	}

	public abstract function constructGallery(PDO $dbh): Gallery;

	public abstract function constructGalleryPermissionChecker(): GalleryPermissionChecker;
}
?>
