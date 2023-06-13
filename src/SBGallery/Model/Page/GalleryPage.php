<?php
namespace SBGallery\Model\Page;
use PDO;
use SBLayout\Model\Page\ContentPage;
use SBData\Model\ParameterMap;
use SBData\Model\Value\Value;
use SBData\Model\Value\PageValue;
use SBData\Model\Value\AcceptableFileNameValue;
use SBCrud\Model\Page\CRUDMasterPage;
use SBGallery\Model\Gallery;
use SBGallery\Model\GalleryPermissionChecker;
use SBGallery\Model\Page\Content\GalleryContents;
use SBGallery\Model\Page\Content\AlbumContents;
use SBGallery\Model\Page\Settings\GalleryPageSettings;

/**
 * A page that displays a gallery and redirects to sub pages that manage all gallery operations.
 */
class GalleryPage extends CRUDMasterPage
{
	/** A connection handler to the gallery database */
	public PDO $dbh;

	/** An object that contains all configurable properties of the gallery */
	public GalleryPageSettings $settings;

	/** An object that checks whether the user has write permissions to the gallery */
	public GalleryPermissionChecker $checker;

	/** Gallery that the page and sub pages manage */
	public Gallery $gallery;

	/** An object that contains the content sections of an album page */
	public AlbumContents $albumContents;

	/**
	 * Constructs a new gallery page instance.
	 *
	 * @param $dbh A connection handler to the gallery database
	 * @param $settings An object that contains all configurable properties of the gallery
	 * @param $checker An object that checks whether the user has write permissions to the gallery
	 * @param $contents Specifies the content to be displayed in every content section. null constructs a default object updating the 'content' section
	 */
	public function __construct(PDO $dbh, GalleryPageSettings $settings, GalleryPermissionChecker $checker, GalleryContents $contents = null)
	{
		if($contents === null)
			$contents = new GalleryContents();

		$this->albumContents = $contents->constructAlbumContents($settings->albumEditorLabelsFile);

		$this->gallery = new Gallery($dbh, $settings->gallerySettings);

		parent::__construct($settings->galleryPageLabels->title, "albumId", $contents, array(
			"create_album" => new GalleryOperationPage($this->gallery, $settings->albumPageLabels->createAlbum, $this->albumContents, $checker, $settings->gallerySettings->operationParam),
			"insert_album" => new GalleryOperationPage($this->gallery, $settings->albumPageLabels->insertAlbum, $this->albumContents, $checker, $settings->gallerySettings->operationParam),
		), $settings->galleryPageLabels->invalidQueryParameterMessage, $settings->galleryPageLabels->invalidOperationMessage, $settings->gallerySettings->operationParam, $settings->galleryPageMenuItem);

		$this->dbh = $dbh;
		$this->settings = $settings;
		$this->checker = $checker;
	}

	/**
	 * @see MasterPage::checkField()
	 */
	public function createParamValue(): Value
	{
		return new AcceptableFileNameValue(true, 255);
	}

	/**
	 * @see MasterPage::createRequestParameterMap()
	 */
	public function createRequestParameterMap(): ParameterMap
	{
		if($this->gallery->settings->galleryPageSize === null)
			return new ParameterMap();
		else
		{
			return new ParameterMap(array(
				"galleryPage" => new PageValue()
			));
		}
	}

	/**
	 * @see MasterPage::createDetailPage()
	 */
	public function createDetailPage(array $query): ?ContentPage
	{
		return new AlbumPage($this->gallery, $query["albumId"], $this->settings, $this->checker, $this->albumContents);
	}
}
?>
