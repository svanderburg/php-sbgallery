<?php
require_once("crud/model/CRUDModel.class.php");
require_once(dirname(__FILE__)."/../Picture.class.php");

class PictureCRUDModel extends CRUDModel
{
	public $picture;

	public $checker;

	public $crudPage;

	public function __construct(CRUDPage $crudPage, Picture $picture)
	{
		parent::__construct($crudPage);
		$this->picture = $picture;
		$this->checker = $crudPage->constructGalleryPermissionChecker();
		$this->crudPage = $crudPage;
	}

	private function createPicture()
	{
		$this->picture->create($this->keyFields["albumId"]->value);
	}

	private function insertPicture()
	{
		if($this->picture->insert($_REQUEST))
		{
			header("Location: ".$_SERVER["PHP_SELF"]."/".$this->picture->entity["PICTURE_ID"]);
			exit;
		}
	}

	private function viewPicture()
	{
		$this->picture->view($this->keyFields["pictureId"]->value, $this->keyFields["albumId"]->value);
		$this->crudPage->title = $this->picture->entity["Title"];
	}

	private function updatePicture()
	{
		if($this->picture->update($_REQUEST))
		{
			$parentURL = dirname(dirname($_SERVER["PHP_SELF"]));
			
			if($parentURL == "/")
				$parentURL = "";

			header("Location: ".$parentURL."/".$this->picture->entity["ALBUM_ID"]."/".$this->picture->entity["PICTURE_ID"]);
			exit;
		}

		$this->crudPage->title = $this->form->fields["Title"]->value;
	}

	private function removePicture()
	{
		$this->picture->remove($this->keyFields["pictureId"]->value, $this->keyFields["albumId"]->value);
		header("Location: ".$_SERVER['HTTP_REFERER']);
		exit();
	}

	private function removePictureImage()
	{
		$this->picture->removePictureImage($this->keyFields["pictureId"]->value, $this->keyFields["albumId"]->value);
		header("Location: ".$_SERVER['HTTP_REFERER']);
		exit();
	}

	private function moveLeftPicture()
	{
		$this->picture->moveLeft($this->keyFields["pictureId"]->value, $this->keyFields["albumId"]->value);
		header("Location: ".$_SERVER['HTTP_REFERER']);
		exit();
	}

	private function moveRightPicture()
	{
		$this->picture->moveRight($this->keyFields["pictureId"]->value, $this->keyFields["albumId"]->value);
		header("Location: ".$_SERVER['HTTP_REFERER']);
		exit();
	}

	private function setAsThumbnailPicture()
	{
		$this->picture->setAsThumbnail($this->keyFields["pictureId"]->value, $this->keyFields["albumId"]->value);
		header("Location: ".$_SERVER['HTTP_REFERER']);
		exit();
	}

	public function executeOperation()
	{
		if(array_key_exists("__operation", $_REQUEST))
		{
			if($this->checker->checkWritePermissions())
			{
				switch($_REQUEST["__operation"])
				{
					case "create_picture":
						$this->createPicture();
						break;
					case "insert_picture":
						$this->insertPicture();
						break;
					case "update_picture":
						$this->updatePicture();
						break;
					case "remove_picture":
						$this->removePicture();
						break;
					case "remove_picture_image":
						$this->removePictureImage();
						break;
					case "moveleft_picture":
						$this->moveLeftPicture();
						break;
					case "moveright_picture":
						$this->moveRightPicture();
						break;
					case "setasthumbnail_picture":
						$this->setAsThumbnailPicture();
						break;
					default:
						$this->viewPicture();
				}
			}
			else
			{
				header("HTTP/1.1 403 Forbidden");
				throw new Exception("No permissions to modify a picture!");
			}
		}
		else
			$this->viewPicture();
	}
}
?>
