<?php
namespace SBGallery\Model;
use SBGallery\Model\Settings\AlbumSettings;

/**
 * Representation of a form that can be used to upload multiple pictures in one operation.
 */
class PicturesUploader
{
	/** The ID of the album */
	public string $albumId;

	/** Object that contains album settings */
	public AlbumSettings $settings;

	/**
	 * Constructs a new pictures uploader instance that can be used to
	 * upload multiple pictures into an album.
	 *
	 * @param $albumId The ID of the album
	 * @param $settings Object that contains album settings
	 */
	public function __construct(string $albumId, AlbumSettings $settings)
	{
		$this->albumId = $albumId;
		$this->settings = $settings;
	}
}
?>
