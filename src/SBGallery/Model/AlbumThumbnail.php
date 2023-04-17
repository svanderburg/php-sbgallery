<?php
namespace SBGallery\Model;

/**
 * Representation of an album thumbnail that redirects the user to the album.
 */
class AlbumThumbnail
{
	/** The ID of the album */
	public string $albumId;

	/** The ID of the picture or null if it was not yet inserted into the database */
	public ?string $pictureId;

	/** Title of the album */
	public string $title;

	/** File type of the thumbail image or null if there is no image */
	public ?string $fileType;

	/**
	 * Constructs a new album thumbnail image.
	 *
	 * @param $albumId The ID of the album
	 * @param $pictureId The ID of the picture
	 * @param $title Title of the album
	 * @param $fileType File type of the thumbail image
	 */
	public function __construct(string $albumId, ?string $pictureId, string $title, ?string $fileType)
	{
		$this->albumId = $albumId;
		$this->pictureId = $pictureId;
		$this->title = $title;
		$this->fileType = $fileType;
	}
}
?>
