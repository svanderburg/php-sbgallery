<?php
namespace SBGallery\Model\CRUD;

use SBCrud\Model\CRUDModel;
use SBCrud\Model\CRUDPage;
use SBGallery\Model\Gallery;

class GalleryCRUDModel extends CRUDModel
{
	public $gallery;

	public $checker;

	public function __construct(CRUDPage $crudPage, Gallery $gallery)
	{
		parent::__construct($crudPage);
		$this->gallery = $gallery;
		$this->checker = $crudPage->constructGalleryPermissionChecker();
	}

	public function executeOperation()
	{
	}
}
?>
