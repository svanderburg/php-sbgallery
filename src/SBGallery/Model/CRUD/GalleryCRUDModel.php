<?php
namespace SBGallery\Model\CRUD;

use SBCrud\Model\CRUDModel;
use SBCrud\Model\CRUDPage;
use SBGallery\Model\Gallery;
use SBGallery\Model\GalleryPermissionChecker;

class GalleryCRUDModel extends CRUDModel
{
	public Gallery $gallery;

	public GalleryPermissionChecker $checker;

	public function __construct(CRUDPage $crudPage, Gallery $gallery)
	{
		parent::__construct($crudPage);
		$this->gallery = $gallery;
		$this->checker = $crudPage->constructGalleryPermissionChecker();
	}

	public function executeOperation(): void
	{
	}
}
?>
