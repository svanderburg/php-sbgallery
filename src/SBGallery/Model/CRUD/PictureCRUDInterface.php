<?php
namespace SBGallery\Model\CRUD;
use Exception;
use SBLayout\Model\Route;
use SBLayout\Model\PageForbiddenException;
use SBData\Model\Table\Anchor\AnchorRow;
use SBCrud\Model\CRUD\CRUDInterface;
use SBCrud\Model\Page\CRUDPage;
use SBGallery\Model\Picture;
use SBGallery\Model\GalleryPermissionChecker;

class PictureCRUDInterface extends CRUDInterface
{
	public Route $route;

	public CRUDPage $crudPage;

	public GalleryPermissionChecker $checker;

	public Picture $picture;

	public function __construct(Route $route, CRUDPage $crudPage, GalleryPermissionChecker $checker)
	{
		parent::__construct($crudPage);
		$this->route = $route;
		$this->crudPage = $crudPage;
		$this->checker = $checker;
	}

	private function createPicture(): void
	{
		$this->picture = $this->crudPage->parentPage->album->constructPicture($GLOBALS["query"]["albumId"]);
		$this->picture->create($GLOBALS["query"]["albumId"]);
	}

	private function insertPicture(): void
	{
		$this->picture = $this->crudPage->parentPage->album->constructPicture($GLOBALS["query"]["albumId"]);

		if($this->picture->insert($_REQUEST))
		{
			header("Location: ".$_SERVER["PHP_SELF"]."/".rawurlencode($this->picture->entity["PICTURE_ID"]));
			exit;
		}
	}

	private function viewPicture(): void
	{
		$this->picture = $this->crudPage->picture;
		$this->picture->view($GLOBALS["query"]["pictureId"], $GLOBALS["query"]["albumId"]);
	}

	private function updatePicture(): void
	{
		$this->picture = $this->crudPage->parentPage->picture;

		if($this->picture->update($_REQUEST))
		{
			header("Location: ".$this->route->composeParentPageURL($_SERVER["SCRIPT_NAME"])."/".rawurlencode($this->picture->entity["PICTURE_ID"]));
			exit;
		}
	}

	private function removePicture(): void
	{
		$this->picture = $this->crudPage->parentPage->picture;

		$this->picture->remove($GLOBALS["query"]["pictureId"], $GLOBALS["query"]["albumId"]);
		header("Location: ".$this->route->composeParentPageURL($_SERVER["SCRIPT_NAME"]).AnchorRow::composePreviousRowFragment("picture"));
		exit();
	}

	private function removePictureImage(): void
	{
		$this->picture = $this->crudPage->parentPage->picture;

		$this->picture->removePictureImage($GLOBALS["query"]["pictureId"], $GLOBALS["query"]["albumId"]);
		header("Location: ".$_SERVER["PHP_SELF"]);
		exit();
	}

	private function moveLeftPicture(): void
	{
		$this->picture = $this->crudPage->parentPage->picture;

		if($this->picture->moveLeft($GLOBALS["query"]["pictureId"], $GLOBALS["query"]["albumId"]))
			$rowFragment = AnchorRow::composePreviousRowFragment("picture");
		else
			$rowFragment = AnchorRow::composeRowFragment("picture");

		header("Location: ".$this->route->composeParentPageURL($_SERVER["SCRIPT_NAME"]).$rowFragment);
		exit();
	}

	private function moveRightPicture(): void
	{
		$this->picture = $this->crudPage->parentPage->picture;

		if($this->picture->moveRight($GLOBALS["query"]["pictureId"], $GLOBALS["query"]["albumId"]))
			$rowFragment = AnchorRow::composeNextRowFragment("picture");
		else
			$rowFragment = AnchorRow::composeRowFragment("picture");

		header("Location: ".$this->route->composeParentPageURL($_SERVER["SCRIPT_NAME"]).$rowFragment);
		exit();
	}

	private function setAsThumbnailPicture(): void
	{
		$this->picture = $this->crudPage->parentPage->picture;

		$this->picture->setAsThumbnail($GLOBALS["query"]["pictureId"], $GLOBALS["query"]["albumId"]);
		header("Location: ".$this->route->composeParentPageURL($_SERVER["SCRIPT_NAME"]).AnchorRow::composeRowFragment("picture"));
		exit();
	}

	public function executeCRUDOperation(?string $operation): void
	{
		if($operation === null)
			$this->viewPicture();
		else
		{
			if($this->checker->checkWritePermissions())
			{
				switch($operation)
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
				}
			}
			else
				throw new PageForbiddenException("No permissions to modify a picture!");
		}
	}
}
?>
