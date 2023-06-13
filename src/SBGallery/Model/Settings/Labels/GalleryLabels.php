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

	public string $previous;

	public string $next;

	public string $cannotFindAlbum;

	public function __construct(string $addAlbum = "Add album",
		string $moveLeft = "Move left",
		string $moveRight = "Move right",
		string $remove = "Remove",
		string $previous = "&laquo; Previous",
		string $next = "Next &raquo;",
		string $cannotFindAlbum = "Cannot find album with ID: ")
	{
		$this->addAlbum = $addAlbum;
		$this->moveLeft = $moveLeft;
		$this->moveRight = $moveRight;
		$this->previous = $previous;
		$this->next = $next;
		$this->remove = $remove;
		$this->cannotFindAlbum = $cannotFindAlbum;
	}
}
?>
