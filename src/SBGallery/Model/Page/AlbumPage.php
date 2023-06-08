<?php
namespace SBGallery\Model\Page;
use SBLayout\Model\PageNotFoundException;
use SBLayout\Model\Page\ContentPage;
use SBData\Model\Value\Value;
use SBData\Model\Value\AcceptableFileNameValue;
use SBCrud\Model\Page\CRUDMasterPage;
use SBCrud\Model\Page\OperationPage;
use SBGallery\Model\Gallery;
use SBGallery\Model\Album;
use SBGallery\Model\GalleryPermissionChecker;
use SBGallery\Model\Exception\AlbumNotFoundException;
use SBGallery\Model\Page\Content\AlbumContents;
use SBGallery\Model\Page\Settings\AlbumPageSettings;

/**
 * A page that displays an album and redirects to sub pages that manage all album operations.
 */
class AlbumPage extends CRUDMasterPage
{
	public Gallery $gallery;

	public Album $album;

	public AlbumPageSettings $settings;

	public GalleryPermissionChecker $checker;

	public function __construct(Gallery $gallery, string $albumId, AlbumPageSettings $settings, GalleryPermissionChecker $checker, AlbumContents $contents = null)
	{
		if($contents === null)
			$contents = new AlbumContents($settings->albumEditorLabelsFile);

		$this->pictureContents = $contents->constructPictureContents($settings->pictureEditorLabelsFile);

		try
		{
			$this->album = $gallery->queryAlbum($albumId);
		}
		catch(AlbumNotFoundException $ex)
		{
			throw new PageNotFoundException($ex->getMessage());
		}

		parent::__construct($settings->albumPageLabels->title, "pictureId", $contents, array(
			"create_picture" => new AlbumOperationPage($this->album, $settings->picturePageLabels->createPicture, $this->pictureContents, $checker, $gallery->settings->operationParam),
			"insert_picture" => new AlbumOperationPage($this->album, $settings->picturePageLabels->insertPicture, $this->pictureContents, $checker, $gallery->settings->operationParam),
			"update_album" => new GalleryOperationPage($gallery, $settings->albumPageLabels->updateAlbum, $contents, $checker, $gallery->settings->operationParam),
			"remove_album" => new GalleryOperationPage($gallery, $settings->albumPageLabels->removeAlbum, $contents, $checker, $gallery->settings->operationParam),
			"moveleft_album" => new GalleryOperationPage($gallery, $settings->albumPageLabels->moveLeft, $contents, $checker, $gallery->settings->operationParam),
			"moveright_album" => new GalleryOperationPage($gallery, $settings->albumPageLabels->moveRight, $contents, $checker, $gallery->settings->operationParam),
			"add_multiple_pictures" => new GalleryOperationPage($gallery, $settings->albumPageLabels->addMultiplePictures, $contents->constructMultiplePictureContents(), $checker, $gallery->settings->operationParam),
			"insert_multiple_pictures" => new GalleryOperationPage($gallery, $settings->albumPageLabels->insertMultiplePictures, $contents, $checker, $gallery->settings->operationParam),
		), $settings->albumPageLabels->invalidQueryParameterMessage, $settings->albumPageLabels->invalidOperationMessage, $gallery->settings->operationParam, $settings->albumMenuItem);

		$this->gallery = $gallery;
		$this->settings = $settings;
		$this->checker = $checker;

		$this->title = $this->album->fields["Title"]->exportValue();
	}

	public function createParamValue(): Value
	{
		return new AcceptableFileNameValue(true, 255);
	}

	public function createDetailPage(array $query): ?ContentPage
	{
		return new PicturePage($this->album, $query["pictureId"], $this->settings, $this->checker, $this->pictureContents);
	}
}
?>
