<?php
namespace SBGallery\Model\Page;
use SBLayout\Model\Page\Content\Contents;
use SBCrud\Model\Page\OperationPage;
use SBGallery\Model\Gallery;
use SBGallery\Model\GalleryPermissionChecker;

/**
 * A page that performs the execution of a CRUD operation for a gallery
 */
class GalleryOperationPage extends OperationPage
{
	public Gallery $gallery;

	public GalleryPermissionChecker $checker;

	public function __construct(Gallery $gallery, string $title, Contents $contents, GalleryPermissionChecker $checker, string $operationParam = "__operation")
	{
		parent::__construct($title, $contents, $operationParam, dirname(__FILE__)."/../../View/HTML/menuitems/galleryoperation.php");
		$this->gallery = $gallery;
		$this->checker = $checker;
	}
}
?>
