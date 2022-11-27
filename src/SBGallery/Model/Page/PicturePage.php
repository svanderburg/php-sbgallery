<?php
namespace SBGallery\Model\Page;
use SBLayout\Model\PageNotFoundException;
use SBCrud\Model\Page\CRUDDetailPage;
use SBCrud\Model\Page\OperationPage;
use SBGallery\Model\Album;
use SBGallery\Model\Picture;
use SBGallery\Model\GalleryPermissionChecker;
use SBGallery\Model\Page\Content\PictureContents;

class PicturePage extends CRUDDetailPage
{
	public AlbumPage $albumPage;

	public Picture $picture;

	public function __construct(AlbumPage $albumPage, Album $album, string $pictureId, string $albumId, string $title = "Picture", PictureContents $contents = null)
	{
		if($contents === null)
			$contents = new PictureContents();

		$this->picture = $album->constructPicture($albumId);

		parent::__construct($title, $contents, array(
			"update_picture" => new GalleryOperationPage($this, $this->picture->labels["Update picture"], $contents),
			"remove_picture" => new GalleryOperationPage($this, $this->picture->labels["Remove picture"], $contents),
			"remove_picture_image" => new GalleryOperationPage($this, $this->picture->labels["Remove picture image"], $contents),
			"moveleft_picture" => new GalleryOperationPage($this, $this->picture->labels["Move left"], $contents),
			"moveright_picture" => new GalleryOperationPage($this, $this->picture->labels["Move right"], $contents),
			"setasthumbnail_picture" => new GalleryOperationPage($this, $this->picture->labels["Set picture as thumbnail"], $contents)
		));

		$this->albumPage = $albumPage;

		$this->picture->fetchEntity($pictureId, $albumId);
		if($this->picture->entity === false)
			throw new PageNotFoundException($this->picture->labels["Cannot find picture:"]." ".$pictureId);
		else
			$this->title = $this->picture->entity["Title"];
	}

	public function constructGalleryPermissionChecker(): GalleryPermissionChecker
	{
		return $this->albumPage->galleryPage->constructGalleryPermissionChecker();
	}
}
?>
