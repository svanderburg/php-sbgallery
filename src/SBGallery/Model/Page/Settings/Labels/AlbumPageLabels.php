<?php
namespace SBGallery\Model\Page\Settings\Labels;

/**
 * Collects all labels that an album page displays
 */
class AlbumPageLabels extends CRUDMasterPageLabels
{
	public string $title;

	public string $invalidQueryParameterMessage;

	public string $invalidOperationMessage;

	public string $createAlbum;

	public string $insertAlbum;

	public string $updateAlbum;

	public string $removeAlbum;

	public string $moveLeft;

	public string $moveRight;

	public string $addMultiplePictures;

	public string $insertMultiplePictures;

	public function __construct(string $title = "Album",
		string $invalidQueryParameterMessage = "Invalid album with identifier:",
		string $invalidOperationMessage = "Invalid operation:",
		string $createAlbum = "Create album",
		string $insertAlbum = "Insert album",
		string $updateAlbum = "Update album",
		string $removeAlbum = "Remove album",
		string $moveLeft = "Move left",
		string $moveRight = "Move right",
		string $addMultiplePictures = "Add multiple pictures",
		string $insertMultiplePictures = "Insert multiple pictures")
	{
		parent::__construct($title, $invalidQueryParameterMessage, $invalidOperationMessage);
		$this->createAlbum = $createAlbum;
		$this->insertAlbum = $insertAlbum;
		$this->updateAlbum = $updateAlbum;
		$this->removeAlbum = $removeAlbum;
		$this->moveLeft = $moveLeft;
		$this->moveRight = $moveRight;
		$this->addMultiplePictures = $addMultiplePictures;
		$this->insertMultiplePictures = $insertMultiplePictures;
	}
}
?>
