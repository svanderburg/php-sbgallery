<?php
namespace SBGallery\Model\CRUD;
use Exception;
use SBLayout\Model\Route;
use SBData\Model\Table\Anchor\AnchorRow;
use SBCrud\Model\Page\CRUDPage;
use SBCrud\Model\CRUD\CRUDInterface;
use SBGallery\Model\Album;
use SBGallery\Model\GalleryPermissionChecker;

class AlbumCRUDInterface extends CRUDInterface
{
	public Route $route;

	public CRUDPage $crudPage;

	public GalleryPermissionChecker $checker;

	public Album $album;

	public function __construct(Route $route, CRUDPage $crudPage, GalleryPermissionChecker $checker)
	{
		parent::__construct($crudPage);
		$this->route = $route;
		$this->crudPage = $crudPage;
		$this->checker = $checker;
	}

	private function createAlbum(): void
	{
		$this->album = $this->crudPage->parentPage->gallery->constructAlbum();
		$this->album->create();
	}

	private function insertAlbum(): void
	{
		$this->album = $this->crudPage->parentPage->gallery->constructAlbum();

		if($this->album->insert($_REQUEST))
		{
			header("Location: ".$_SERVER["PHP_SELF"]."/".rawurlencode($this->album->entity["ALBUM_ID"]));
			exit;
		}
	}

	private function viewAlbum(): void
	{
		$this->album = $this->crudPage->album;
		$this->album->view($GLOBALS["query"]["albumId"]);
	}

	private function updateAlbum(): void
	{
		$this->album = $this->crudPage->parentPage->album;

		if($this->album->update($_REQUEST))
		{
			header("Location: ".$this->route->composeParentPageURL($_SERVER["SCRIPT_NAME"])."/".rawurlencode($this->album->entity["ALBUM_ID"]));
			exit;
		}
	}

	private function removeAlbum(): void
	{
		$this->album = $this->crudPage->parentPage->album;

		$this->album->remove($GLOBALS["query"]["albumId"]);
		header("Location: ".$this->route->composeParentPageURL($_SERVER["SCRIPT_NAME"]).AnchorRow::composePreviousRowFragment("album"));
		exit();
	}

	private function moveLeftAlbum(): void
	{
		$this->album = $this->crudPage->parentPage->album;

		if($this->album->moveLeft($GLOBALS["query"]["albumId"]))
			$rowFragment = AnchorRow::composePreviousRowFragment("album");
		else
			$rowFragment = AnchorRow::composeRowFragment("album");

		header("Location: ".$this->route->composeParentPageURL($_SERVER["SCRIPT_NAME"]).$rowFragment);
		exit();
	}

	private function moveRightAlbum(): void
	{
		$this->album = $this->crudPage->parentPage->album;

		if($this->album->moveRight($GLOBALS["query"]["albumId"]))
			$rowFragment = AnchorRow::composeNextRowFragment("album");
		else
			$rowFragment = AnchorRow::composeRowFragment("album");

		header("Location: ".$this->route->composeParentPageURL($_SERVER["SCRIPT_NAME"]).$rowFragment);
		exit();
	}

	private function insertMultiplePictures(): void
	{
		$this->album = $this->crudPage->parentPage->album;

		$this->album->insertMultiplePictures($GLOBALS["query"]["albumId"], "Image");
		header("Location: ".$_SERVER["PHP_SELF"]);
		exit();
	}

	public function executeCRUDOperation(?string $operation): void
	{
		if($operation === null)
			$this->viewAlbum();
		else
		{
			if($this->checker->checkWritePermissions())
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
