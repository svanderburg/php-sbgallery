<?php
namespace SBGallery\Model\Page;
use SBLayout\Model\PageNotFoundException;
use SBCrud\Model\Page\CRUDDetailPage;
use SBGallery\Model\Album;
use SBGallery\Model\Picture;
use SBGallery\Model\GalleryPermissionChecker;
use SBGallery\Model\Exception\PictureNotFoundException;
use SBGallery\Model\Page\Content\PictureContents;
use SBGallery\Model\Page\Settings\PicturePageSettings;

/**
 * A page that displays a picture and redirects to sub pages that manage all page operations.
 */
class PicturePage extends CRUDDetailPage
{
	public Album $album;

	public Picture $picture;

	public GalleryPermissionChecker $checker;

	public function __construct(Album $album, string $pictureId, PicturePageSettings $settings, GalleryPermissionChecker $checker, PictureContents $contents = null)
	{
		if($contents === null)
			$contents = new PictureContents();

		parent::__construct($settings->picturePageLabels->title, $contents, array(
			"update_picture" => new AlbumOperationPage($album, $settings->picturePageLabels->updatePicture, $contents, $checker, $album->settings->operationParam),
			"remove_picture" => new AlbumOperationPage($album, $settings->picturePageLabels->removePicture, $contents, $checker, $album->settings->operationParam),
			"clear_picture" => new AlbumOperationPage($album, $settings->picturePageLabels->clearPicture, $contents, $checker, $album->settings->operationParam),
			"moveleft_picture" => new AlbumOperationPage($album, $settings->picturePageLabels->moveLeft, $contents, $checker, $album->settings->operationParam),
			"moveright_picture" => new AlbumOperationPage($album, $settings->picturePageLabels->moveRight, $contents, $checker, $album->settings->operationParam),
			"moveright_picture" => new AlbumOperationPage($album, $settings->picturePageLabels->moveRight, $contents, $checker, $album->settings->operationParam),
			"setasthumbnail_picture" => new AlbumOperationPage($album, $settings->picturePageLabels->setPictureAsThumbnail, $contents, $checker, $album->settings->operationParam)
		));
		$this->album = $album;
		$this->checker = $checker;

		try
		{
			$this->picture = $album->queryPicture($pictureId);
			$this->title = $this->picture->form->fields["Title"]->exportValue();
		}
		catch(PictureNotFoundException $ex)
		{
			throw new PageNotFoundException($ex->getMessage());
		}
	}
}
?>
