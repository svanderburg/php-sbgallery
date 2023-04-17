<?php
namespace SBGallery\Model\Page\Settings\Labels;

/**
 * Collects all labels that a picture page displays
 */
class PicturePageLabels
{
	public string $title;

	public string $createPicture;

	public string $insertPicture;

	public string $updatePicture;

	public string $removePicture;

	public string $clearPicture;

	public string $moveLeft;

	public string $moveRight;

	public string $setPictureAsThumbnail;

	public function __construct(string $title = "Picture",
		string $createPicture = "Create picture",
		string $insertPicture = "Insert picture",
		string $updatePicture = "Update picture",
		string $removePicture = "Remove picture",
		string $clearPicture = "Clear picture",
		string $moveLeft = "Move left",
		string $moveRight = "Move right",
		string $setPictureAsThumbnail = "Set picture as thumbnail")
	{
		$this->title = $title;
		$this->createPicture = $createPicture;
		$this->insertPicture = $insertPicture;
		$this->updatePicture = $updatePicture;
		$this->removePicture = $removePicture;
		$this->clearPicture = $clearPicture;
		$this->moveLeft = $moveLeft;
		$this->moveRight = $moveRight;
		$this->setPictureAsThumbnail = $setPictureAsThumbnail;
	}
}
?>
