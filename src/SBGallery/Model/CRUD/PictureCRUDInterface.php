<?php
namespace SBGallery\Model\CRUD;
use Exception;
use SBLayout\Model\Route;
use SBLayout\Model\PageForbiddenException;
use SBData\Model\Table\Anchor\AnchorRow;
use SBCrud\Model\RouteUtils;
use SBCrud\Model\CRUD\CRUDInterface;
use SBCrud\Model\Page\OperationParamPage;
use SBGallery\Model\Picture;

class PictureCRUDInterface extends CRUDInterface
{
	public Route $route;

	public OperationParamPage $operationParamPage;

	public Picture $picture;

	public function __construct(Route $route, OperationParamPage $operationParamPage)
	{
		parent::__construct($operationParamPage);
		$this->route = $route;
		$this->operationParamPage = $operationParamPage;
	}

	private function viewPicture(): void
	{
		$this->picture = $this->operationParamPage->picture;
	}

	private function createPicture(): void
	{
		$this->picture = $this->operationParamPage->album->newPicture();
	}

	private function insertPicture(): void
	{
		$this->picture = $this->operationParamPage->album->newPicture();
		$this->picture->importValues($_REQUEST);
		$this->picture->checkFields();

		if($this->picture->checkValid())
		{
			$this->operationParamPage->album->insertPicture($this->picture);

			header("Location: ".RouteUtils::composeSelfURL()."/".rawurlencode($this->picture->fields["PICTURE_ID"]->exportValue()));
			exit();
		}
	}

	private function updatePicture(): void
	{
		$this->picture = $this->operationParamPage->album->queryPicture($GLOBALS["query"]["pictureId"]);
		$this->picture->importValues($_REQUEST);
		$this->picture->checkFields();

		if($this->picture->checkValid())
		{
			$this->operationParamPage->album->updatePicture($GLOBALS["query"]["pictureId"], $this->picture);
			header("Location: ".$this->route->composeParentPageURL($_SERVER["SCRIPT_NAME"])."/".rawurlencode($this->picture->fields["PICTURE_ID"]->exportValue()));
			exit();
		}
	}

	private function removePicture(): void
	{
		$this->operationParamPage->album->removePicture($GLOBALS["query"]["pictureId"]);
		header("Location: ".$this->route->composeParentPageURL($_SERVER["SCRIPT_NAME"]).AnchorRow::composePreviousRowFragment($this->operationParamPage->album->settings->anchorPrefix));
		exit();
	}

	private function clearPicture(): void
	{
		$this->operationParamPage->album->clearPicture($GLOBALS["query"]["pictureId"]);
		header("Location: ".RouteUtils::composeSelfURL());
		exit();
	}

	private function moveLeftPicture(): void
	{
		if($this->operationParamPage->album->moveLeftPicture($GLOBALS["query"]["pictureId"]))
			$rowFragment = AnchorRow::composePreviousRowFragment($this->operationParamPage->album->settings->anchorPrefix);
		else
			$rowFragment = AnchorRow::composeRowFragment($this->operationParamPage->album->settings->anchorPrefix);

		header("Location: ".$this->route->composeParentPageURL($_SERVER["SCRIPT_NAME"]).$rowFragment);
		exit();
	}

	private function moveRightPicture(): void
	{
		if($this->operationParamPage->album->moveRightPicture($GLOBALS["query"]["pictureId"]))
			$rowFragment = AnchorRow::composeNextRowFragment($this->operationParamPage->album->settings->anchorPrefix);
		else
			$rowFragment = AnchorRow::composeRowFragment($this->operationParamPage->album->settings->anchorPrefix);

		header("Location: ".$this->route->composeParentPageURL($_SERVER["SCRIPT_NAME"]).$rowFragment);
		exit();
	}

	private function setAsPictureAsThumbnail(): void
	{
		$this->operationParamPage->album->setAsThumbnail($GLOBALS["query"]["pictureId"]);
		header("Location: ".$this->route->composeParentPageURL($_SERVER["SCRIPT_NAME"]).AnchorRow::composeRowFragment($this->operationParamPage->album->settings->anchorPrefix));
		exit();
	}

	public function executeCRUDOperation(?string $operation): void
	{
		if($operation === null)
			$this->viewPicture();
		else
		{
			if($this->operationParamPage->checker->checkWritePermissions())
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
