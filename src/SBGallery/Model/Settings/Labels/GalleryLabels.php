<?php
namespace SBGallery\Model\Settings\Labels;

/**
 * Collects all labels that a gallery displays
 */
class GalleryLabels
{
	public string $addAlbum;

	public string $moveLeft;

	public string $moveRight;

	public string $remove;

	public string $cannotFindAlbum;

	public function __construct(string $addAlbum = "Add album",
		string $moveLeft = "Move left",
		string $moveRight = "Move right",
		string $remove = "Remove",
		string $cannotFindAlbum = "Cannot find album with ID: ")
	{
		$this->addAlbum = $addAlbum;
		$this->moveLeft = $moveLeft;
		$this->moveRight = $moveRight;
		$this->remove = $remove;
		$this->cannotFindAlbum = $cannotFindAlbum;
	}
}
?>
