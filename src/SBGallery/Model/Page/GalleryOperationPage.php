<?php
namespace SBGallery\Model\Page;
use SBLayout\Model\Page\Content\Contents;
use SBCrud\Model\Page\CRUDPage;
use SBCrud\Model\Page\OperationPage;
use SBGallery\Model\GalleryPermissionChecker;

class GalleryOperationPage extends OperationPage
{
	public CRUDPage $parentPage;

	public function __construct(CRUDPage $parentPage, string $title, Contents $contents, string $operationParam = "__operation")
	{
		parent::__construct($title, $contents, $operationParam, dirname(__FILE__)."/../../View/HTML/menuitems/galleryoperation.php");
		$this->parentPage = $parentPage;
	}

	public function constructGalleryPermissionChecker(): GalleryPermissionChecker
	{
		return $this->parentPage->constructGalleryPermissionChecker();
	}
}
?>
