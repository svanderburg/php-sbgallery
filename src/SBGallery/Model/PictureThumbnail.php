<?php
namespace SBGallery\Model;

/**
 * Representation of a thumbnail of a picture
 */
class PictureThumbnail
{
	/** The ID of the picture */
	public string $pictureId;

	/** Title of the picture */
	public string $title;

	/** File type of the picture or null if it was not uploaded */
	public ?string $fileType;

	/** The ID of the album in which the picture is stored */
	public string $albumId;

	/**
	 * Constructs a new picture thumbnail instance.
	 *
	 * @param $pictureId The ID of the picture
	 * @param $title Title of the picture
	 * @param $fileType File type of the picture
	 * @param $albumId The ID of the album in which the picture is stored
	 */
	public function __construct(string $pictureId, string $title, ?string $fileType, string $albumId)
	{
		$this->pictureId = $pictureId;
		$this->title = $title;
		$this->fileType = $fileType;
		$this->albumId = $albumId;
	}
}
?>
