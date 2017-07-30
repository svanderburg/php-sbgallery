<?php
namespace SBGallery\Model\CRUD;
use SBCrud\Model\CRUDModel;
use SBCrud\Model\CRUDPage;
use SBGallery\Model\Album;

class AlbumCRUDModel extends CRUDModel
{
	public $album;

	public $checker;

	public $crudPage;

	public function __construct(CRUDPage $crudPage, Album $album)
	{
		parent::__construct($crudPage);
		$this->album = $album;
		$this->checker = $crudPage->constructGalleryPermissionChecker();
		$this->crudPage = $crudPage;
	}

	private function createAlbum()
	{
		$this->album->create();
	}

	private function insertAlbum()
	{
		if($this->album->insert($_REQUEST))
		{
			header("Location: ".$_SERVER["PHP_SELF"]."/".$this->album->entity["ALBUM_ID"]);
			exit;
		}
	}

	private function viewAlbum()
	{
		$this->album->view($this->keyFields["albumId"]->value);
		$this->crudPage->title = $this->album->entity["Title"];
	}

	private function updateAlbum()
	{
		if($this->album->update($_REQUEST))
		{
			$parentURL = dirname($_SERVER["PHP_SELF"]);

			if($parentURL == "/")
				$parentURL = "";

			header("Location: ".$parentURL."/".$this->album->entity["ALBUM_ID"]);
			exit;
		}

		$this->crudPage->title = $this->form->fields["Title"]->value;
	}

	private function removeAlbum()
	{
		$this->album->remove($this->keyFields["albumId"]->value);
		header("Location: ".$_SERVER['HTTP_REFERER']);
		exit();
	}

	private function moveLeftAlbum()
	{
		$this->album->moveLeft($this->keyFields["albumId"]->value);
		header("Location: ".$_SERVER['HTTP_REFERER']);
		exit();
	}

	private function moveRightAlbum()
	{
		$this->album->moveRight($this->keyFields["albumId"]->value);
		header("Location: ".$_SERVER['HTTP_REFERER']);
		exit();
	}

	private function insertMultiplePictures()
	{
		$this->album->insertMultiplePictures($this->keyFields["albumId"]->value, "Image");
		header("Location: ".$_SERVER["SCRIPT_NAME"]."/gallery/".$this->keyFields["albumId"]->value);
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
