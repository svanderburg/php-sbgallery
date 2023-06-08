<?php
namespace SBGallery\Model\CRUD;
use Exception;
use SBLayout\Model\Route;
use SBLayout\Model\PageForbiddenException;
use SBData\Model\Table\Anchor\AnchorRow;
use SBCrud\Model\RouteUtils;
use SBCrud\Model\Page\OperationParamPage;
use SBCrud\Model\CRUD\CRUDInterface;
use SBGallery\Model\Album;

class AlbumCRUDInterface extends CRUDInterface
{
	public Route $route;

	public OperationParamPage $operationParamPage;

	public Album $album;

	public function __construct(Route $route, OperationParamPage $operationParamPage)
	{
		parent::__construct($operationParamPage);
		$this->route = $route;
		$this->operationParamPage = $operationParamPage;
	}

	private function viewAlbum(): void
	{
		$this->album = $this->operationParamPage->album;
	}

	private function createAlbum(): void
	{
		$this->album = $this->operationParamPage->gallery->newAlbum();
	}

	private function insertAlbum(): void
	{
		$this->album = $this->operationParamPage->gallery->newAlbum();
		$this->album->importValues($_REQUEST);
		$this->album->checkFields();

		if($this->album->checkValid())
		{
			$this->operationParamPage->gallery->insertAlbum($this->album);
			header("Location: ".RouteUtils::composeSelfURL()."/".rawurlencode($this->album->fields["ALBUM_ID"]->exportValue()));
			exit();
		}
	}

	private function updateAlbum(): void
	{
		$this->album = $this->operationParamPage->gallery->newAlbum($GLOBALS["query"]["albumId"]);
		$this->album->importValues($_REQUEST);
		$this->album->checkFields();

		if($this->album->checkValid())
		{
			$this->operationParamPage->gallery->updateAlbum($GLOBALS["query"]["albumId"], $this->album);
			header("Location: ".$this->route->composeParentPageURL($_SERVER["SCRIPT_NAME"])."/".rawurlencode($this->album->fields["ALBUM_ID"]->exportValue()));
			exit();
		}
	}

	private function removeAlbum(): void
	{
		$this->operationParamPage->gallery->removeAlbum($GLOBALS["query"]["albumId"]);
		header("Location: ".$this->route->composeParentPageURL($_SERVER["SCRIPT_NAME"]).AnchorRow::composePreviousRowFragment($this->operationParamPage->gallery->settings->albumAnchorPrefix));
		exit();
	}

	private function moveLeftAlbum(): void
	{
		if($this->operationParamPage->gallery->moveLeftAlbum($GLOBALS["query"]["albumId"]))
			$rowFragment = AnchorRow::composePreviousRowFragment($this->operationParamPage->gallery->settings->albumAnchorPrefix);
		else
			$rowFragment = AnchorRow::composeRowFragment($this->operationParamPage->gallery->settings->albumAnchorPrefix);

		header("Location: ".$this->route->composeParentPageURL($_SERVER["SCRIPT_NAME"]).$rowFragment);
		exit();
	}

	private function moveRightAlbum(): void
	{
		if($this->operationParamPage->gallery->moveRightAlbum($GLOBALS["query"]["albumId"]))
			$rowFragment = AnchorRow::composeNextRowFragment($this->operationParamPage->gallery->settings->albumAnchorPrefix);
		else
			$rowFragment = AnchorRow::composeRowFragment($this->operationParamPage->gallery->settings->albumAnchorPrefix);

		header("Location: ".$this->route->composeParentPageURL($_SERVER["SCRIPT_NAME"]).$rowFragment);
		exit();
	}

	private function insertMultiplePictures(): void
	{
		$this->album = $this->operationParamPage->gallery->queryAlbum($GLOBALS["query"]["albumId"]);
		$this->album->insertMultiplePictures("Image");

		header("Location: ".RouteUtils::composeSelfURL());
		exit();
	}

	public function executeCRUDOperation(?string $operation): void
	{
		if($operation === null)
			$this->viewAlbum();
		else
		{
			if($this->operationParamPage->checker->checkWritePermissions())
			{
				switch($operation)
				{
					case "create_album":
						$this->createAlbum();
						break;
					case "insert_album":
						$this->insertAlbum();
						break;
					case "update_album":
						$this->updateAlbum();
						break;
					case "remove_album":
						$this->removeAlbum();
						break;
					case "moveleft_album":
						$this->moveLeftAlbum();
						break;
					case "moveright_album":
						$this->moveRightAlbum();
						break;
					case "insert_multiple_pictures":
						$this->insertMultiplePictures();
						break;
				}
			}
			else
				throw new PageForbiddenException("No permissions to modify an album!");
		}
	}
}
?>
