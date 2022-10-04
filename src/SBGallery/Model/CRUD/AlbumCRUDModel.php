<?php
namespace SBGallery\Model\CRUD;
use Exception;
use SBData\Model\Table\Anchor\AnchorRow;
use SBCrud\Model\CRUDModel;
use SBCrud\Model\CRUDPage;
use SBGallery\Model\Album;
use SBGallery\Model\GalleryPermissionChecker;

class AlbumCRUDModel extends CRUDModel
{
	public Album $album;

	public GalleryPermissionChecker $checker;

	public CRUDPage $crudPage;

	public function __construct(CRUDPage $crudPage, Album $album)
	{
		parent::__construct($crudPage);
		$this->album = $album;
		$this->checker = $crudPage->constructGalleryPermissionChecker();
		$this->crudPage = $crudPage;
	}

	private function createAlbum(): void
	{
		$this->album->create();
	}

	private function insertAlbum(): void
	{
		if($this->album->insert($_REQUEST))
		{
			header("Location: ".$_SERVER["PHP_SELF"]."/".$this->album->entity["ALBUM_ID"]);
			exit;
		}
	}

	private function viewAlbum(): void
	{
		$this->album->view($this->keyFields["albumId"]->exportValue());
		$this->crudPage->title = $this->album->entity["Title"];
	}

	private function updateAlbum(): void
	{
		if($this->album->update($_REQUEST))
		{
			$parentURL = dirname($_SERVER["PHP_SELF"]);

			if($parentURL == "/")
				$parentURL = "";

			header("Location: ".$parentURL."/".$this->album->entity["ALBUM_ID"]);
			exit;
		}

		$this->crudPage->title = $this->form->fields["Title"]->exportValue();
	}

	private function removeAlbum(): void
	{
		$this->album->remove($this->keyFields["albumId"]->exportValue());
		header("Location: ".$_SERVER['HTTP_REFERER'].AnchorRow::composePreviousRowFragment("album"));
		exit();
	}

	private function moveLeftAlbum(): void
	{
		if($this->album->moveLeft($this->keyFields["albumId"]->exportValue()))
			$rowFragment = AnchorRow::composePreviousRowFragment("album");
		else
			$rowFragment = AnchorRow::composeRowFragment("album");

		header("Location: ".$_SERVER['HTTP_REFERER'].$rowFragment);
		exit();
	}

	private function moveRightAlbum(): void
	{
		if($this->album->moveRight($this->keyFields["albumId"]->exportValue()))
			$rowFragment = AnchorRow::composeNextRowFragment("album");
		else
			$rowFragment = AnchorRow::composeRowFragment("album");

		header("Location: ".$_SERVER['HTTP_REFERER'].$rowFragment);
		exit();
	}

	private function insertMultiplePictures(): void
	{
		$this->album->insertMultiplePictures($this->keyFields["albumId"]->exportValue(), "Image");
		header("Location: ".$_SERVER["SCRIPT_NAME"]."/gallery/".$this->keyFields["albumId"]->exportValue());
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
					default:
						$this->viewAlbum();
				}
			}
			else
			{
				header("HTTP/1.1 403 Forbidden");
				throw new Exception("No permissions to modify an album!");
			}
		}
		else
			$this->viewAlbum();
	}
}
?>
