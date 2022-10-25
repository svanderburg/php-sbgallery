<?php
namespace SBGallery\Model\CRUD;
use Exception;
use SBData\Model\Table\Anchor\AnchorRow;
use SBCrud\Model\CRUDModel;
use SBCrud\Model\CRUDPage;
use SBGallery\Model\Picture;
use SBGallery\Model\GalleryPermissionChecker;

class PictureCRUDModel extends CRUDModel
{
	public Picture $picture;

	public GalleryPermissionChecker $checker;

	public CRUDPage $crudPage;

	public function __construct(CRUDPage $crudPage, Picture $picture)
	{
		parent::__construct($crudPage);
		$this->picture = $picture;
		$this->checker = $crudPage->constructGalleryPermissionChecker();
		$this->crudPage = $crudPage;
	}

	private function createPicture(): void
	{
		$this->picture->create($this->keyParameterMap->values["albumId"]->value);
	}

	private function insertPicture(): void
	{
		if($this->picture->insert($_REQUEST))
		{
			header("Location: ".$_SERVER["PHP_SELF"]."/".$this->picture->entity["PICTURE_ID"]);
			exit;
		}
	}

	private function viewPicture(): void
	{
		$this->picture->view($this->keyParameterMap->values["pictureId"]->value, $this->keyParameterMap->values["albumId"]->value);
		$this->crudPage->title = $this->picture->entity["Title"];
	}

	private function updatePicture(): void
	{
		if($this->picture->update($_REQUEST))
		{
			$parentURL = dirname(dirname($_SERVER["PHP_SELF"]));
			
			if($parentURL == "/")
				$parentURL = "";

			header("Location: ".$parentURL."/".$this->picture->entity["ALBUM_ID"]."/".$this->picture->entity["PICTURE_ID"]);
			exit;
		}

		$this->crudPage->title = $this->form->fields["Title"]->exportValue();
	}

	private function removePicture(): void
	{
		$this->picture->remove($this->keyParameterMap->values["pictureId"]->value, $this->keyParameterMap->values["albumId"]->value);
		header("Location: ".$_SERVER['HTTP_REFERER'].AnchorRow::composePreviousRowFragment("picture"));
		exit();
	}

	private function removePictureImage(): void
	{
		$this->picture->removePictureImage($this->keyParameterMap->values["pictureId"]->value, $this->keyParameterMap->values["albumId"]->value);
		header("Location: ".$_SERVER['HTTP_REFERER']);
		exit();
	}

	private function moveLeftPicture(): void
	{
		if($this->picture->moveLeft($this->keyParameterMap->values["pictureId"]->value, $this->keyParameterMap->values["albumId"]->value))
			$rowFragment = AnchorRow::composePreviousRowFragment("picture");
		else
			$rowFragment = AnchorRow::composeRowFragment("picture");

		header("Location: ".$_SERVER['HTTP_REFERER'].$rowFragment);
		exit();
	}

	private function moveRightPicture(): void
	{
		if($this->picture->moveRight($this->keyParameterMap->values["pictureId"]->value, $this->keyParameterMap->values["albumId"]->value))
			$rowFragment = AnchorRow::composeNextRowFragment("picture");
		else
			$rowFragment = AnchorRow::composeRowFragment("picture");

		header("Location: ".$_SERVER['HTTP_REFERER'].$rowFragment);
		exit();
	}

	private function setAsThumbnailPicture(): void
	{
		$this->picture->setAsThumbnail($this->keyParameterMap->values["pictureId"]->value, $this->keyParameterMap->values["albumId"]->value);
		header("Location: ".$_SERVER['HTTP_REFERER'].AnchorRow::composeRowFragment("picture"));
		exit();
	}

	public function executeOperation(): void
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
