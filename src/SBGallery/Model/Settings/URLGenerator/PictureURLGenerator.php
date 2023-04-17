<?php
namespace SBGallery\Model\Settings\URLGenerator;

/**
 * Specifies a number of methods that generate URLs to pages that manage the properties of a picture.
 */
interface PictureURLGenerator
{
	/**
	 * Generates a URL to a page that redirects the user to the previous picture in an album.
	 *
	 * @param $albumId ID of the album
	 * @param $pictureId ID of the picture
	 * @return URL to the page displaying the previous picture
	 */
	public function generatePreviousPictureURL(string $albumId, string $pictureId): string;

	/**
	 * Generates a URL to a page that redirects the user to the next picture in an album.
	 *
	 * @param $albumId ID of the album
	 * @param $pictureId ID of the picture
	 * @return URL to the page displaying the next picture
	 */
	public function generateNextPictureURL(string $albumId, string $pictureId): string;

	/**
	 * Generates a URL that a picture form redirects to (that is reponsible for inserting or updating a picture)
	 *
	 * @param $albumId ID of the album to update
	 * @param $pictureId ID of the picture. null indicates that a new album must created
	 * @return The URL where the form should redirect to
	 */
	public function generatePictureFormURL(string $albumId, ?string $pictureId): ?string;

	/**
	 * Generates a URL that clears a picture.
	 *
	 * @param $albumId ID of the album
	 * @param $pictureId ID of the picture
	 * @return URL to the page clearing the picture
	 */
	public function generateClearPictureURL(string $albumId, string $pictureId): string;
}
?>
