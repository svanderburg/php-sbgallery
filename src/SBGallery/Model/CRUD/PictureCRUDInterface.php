<?php
namespace SBGallery\Model\CRUD;
use Exception;
use SBLayout\Model\Route;
use SBLayout\Model\PageForbiddenException;
use SBData\Model\Table\Anchor\AnchorRow;
use SBCrud\Model\RouteUtils;
use SBCrud\Model\CRUD\CRUDInterface;
use SBCrud\Model\Page\CRUDPage;
use SBGallery\Model\Picture;

class PictureCRUDInterface extends CRUDInterface
{
	public Route $route;

	public CRUDPage $crudPage;

	public Picture $picture;

	public function __construct(Route $route, CRUDPage $crudPage)
	{
		parent::__construct($crudPage);
		$this->route = $route;
		$this->crudPage = $crudPage;
	}

	private function viewPicture(): void
	{
		$this->picture = $this->crudPage->picture;
	}

	private function createPicture(): void
	{
		$this->picture = $this->crudPage->album->newPicture();
	}

	private function insertPicture(): void
	{
		$this->picture = $this->crudPage->album->newPicture();
		$this->picture->importValues($_REQUEST);
		$this->picture->checkFields();

		if($this->picture->checkValid())
		{
			$this->crudPage->album->insertPicture($this->picture);

			header("Location: ".RouteUtils::composeSelfURL()."/".rawurlencode($this->picture->form->fields["PICTURE_ID"]->exportValue()));
			exit();
		}
	}

	private function updatePicture(): void
	{
		$this->picture = $this->crudPage->album->queryPicture($GLOBALS["query"]["pictureId"]);
		$this->picture->importValues($_REQUEST);
		$this->picture->checkFields();

		if($this->picture->checkValid())
		{
			$this->crudPage->album->updatePicture($GLOBALS["query"]["pictureId"], $this->picture);
			header("Location: ".$this->route->composeParentPageURL($_SERVER["SCRIPT_NAME"])."/".rawurlencode($this->picture->form->fields["PICTURE_ID"]->exportValue()));
			exit();
		}
	}

	private function removePicture(): void
	{
		$this->crudPage->album->removePicture($GLOBALS["query"]["pictureId"]);
		header("Location: ".$this->route->composeParentPageURL($_SERVER["SCRIPT_NAME"]).AnchorRow::composePreviousRowFragment($this->crudPage->album->settings->anchorPrefix));
		exit();
	}

	private function clearPicture(): void
	{
		$this->crudPage->album->clearPicture($GLOBALS["query"]["pictureId"]);
		header("Location: ".RouteUtils::composeSelfURL());
		exit();
	}

	private function moveLeftPicture(): void
	{
		if($this->crudPage->album->moveLeftPicture($GLOBALS["query"]["pictureId"]))
			$rowFragment = AnchorRow::composePreviousRowFragment($this->crudPage->album->settings->anchorPrefix);
		else
			$rowFragment = AnchorRow::composeRowFragment($this->crudPage->album->settings->anchorPrefix);

		header("Location: ".$this->route->composeParentPageURL($_SERVER["SCRIPT_NAME"]).$rowFragment);
		exit();
	}

	private function moveRightPicture(): void
	{
		if($this->crudPage->album->moveRightPicture($GLOBALS["query"]["pictureId"]))
			$rowFragment = AnchorRow::composeNextRowFragment($this->crudPage->album->settings->anchorPrefix);
		else
			$rowFragment = AnchorRow::composeRowFragment($this->crudPage->album->settings->anchorPrefix);

		header("Location: ".$this->route->composeParentPageURL($_SERVER["SCRIPT_NAME"]).$rowFragment);
		exit();
	}

	private function setAsPictureAsThumbnail(): void
	{
		$this->crudPage->album->setAsThumbnail($GLOBALS["query"]["pictureId"]);
		header("Location: ".$this->route->composeParentPageURL($_SERVER["SCRIPT_NAME"]).AnchorRow::composeRowFragment($this->crudPage->album->settings->anchorPrefix));
		exit();
	}

	public function executeCRUDOperation(?string $operation): void
	{
		if($operation === null)
			$this->viewPicture();
		else
		{
			if($this->crudPage->checker->checkWritePermissions())
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
					case "clear_picture":
						$this->clearPicture();
						break;
					case "moveleft_picture":
						$this->moveLeftPicture();
						break;
					case "moveright_picture":
						$this->moveRightPicture();
						break;
					case "setasthumbnail_picture":
						$this->setAsPictureAsThumbnail();
						break;
				}
			}
			else
				throw new PageForbiddenException("No permissions to modify a picture!");
		}
	}
}
?>
