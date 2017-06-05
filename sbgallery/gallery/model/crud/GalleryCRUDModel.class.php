<?php
require_once("crud/model/CRUDModel.class.php");
require_once(dirname(__FILE__)."/../Gallery.class.php");

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
