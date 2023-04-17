<?php
namespace SBGallery\Model\Page;
use SBLayout\Model\Page\Content\Contents;
use SBCrud\Model\Page\OperationPage;
use SBGallery\Model\Album;
use SBGallery\Model\GalleryPermissionChecker;

/**
 * A page that performs the execution of a CRUD operation for an album
 */
class AlbumOperationPage extends OperationPage
{
	public Album $album;

	public GalleryPermissionChecker $checker;

	public function __construct(Album $album, string $title, Contents $contents, GalleryPermissionChecker $checker, string $operationParam = "__operation")
	{
		parent::__construct($title, $contents, $operationParam, dirname(__FILE__)."/../../View/HTML/menuitems/galleryoperation.php");
		$this->album = $album;
		$this->checker = $checker;
	}
}
?>
