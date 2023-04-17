<?php
namespace SBGallery\Model\Settings\URLGenerator;

/**
 * Specifies a number of methods that generate URLs to pages that manage the content of an album
 */
interface AlbumURLGenerator extends PictureURLGenerator
{
	/**
	 * Generates a URL to a page that displays a picture from the album.
	 *
	 * @param $albumId ID of the album
	 * @param $pictureId ID of the picture
	 * @return URL to the picture with the given IDs
	 */
	public function generatePictureURL(string $albumId, string $pictureId): string;

	/**
	 * Generates a URL to a page that allows the user to add a picture to an album.
	 *
	 * @param $albumId ID of the album
	 * @return URL to the add picture page
	 */
	public function generateAddPictureURL(string $albumId): string;

	/**
	 * Generates a URL to a page that allows the user to add multiple pictures to an album.
	 *
	 * @param $albumId ID of the album
	 * @return URL to the add multiple pictures page
	 */
	public function generateAddMultiplePicturesURL(string $albumId): string;

	/**
	 * Generates a URL to a page that moves a picture left in the album.
	 *
	 * @param $id Anchor ID of the picture in the album so that the user gets redirected back to the right scroll position
	 * @param $albumId ID of the album
	 * @param $pictureId ID of the picture
	 * @return URL to the page that moves the picture
	 */
	public function generateMovePictureLeftURL(int $id, string $albumId, string $pictureId): string;

	/**
	 * Generates a URL to a page that moves a picture right in the album.
	 *
	 * @param $id Anchor ID of the picture in the album so that the user gets redirected back to the right scroll position
	 * @param $albumId ID of the album
	 * @param $pictureId ID of the picture
	 * @return URL to the page that moves the picture
	 */
	public function generateMovePictureRightURL(int $id, string $albumId, string $pictureId): string;

	/**
	 * Generates a URL to a page that sets a picture as a thumbnail for an album.
	 *
	 * @param $id Anchor ID of the picture in the album so that the user gets redirected back to the right scroll position
	 * @param $albumId ID of the album
	 * @param $pictureId ID of the picture
	 * @return URL to the page that sets the thumbnail
	 */
	public function generateSetAsThumbnailURL(int $id, string $albumId, string $pictureId): string;

	/**
	 * Generates a URL to a page that removes a picture from an album.
	 *
	 * @param $id Anchor ID of the picture in the album so that the user gets redirected back to the right scroll position
	 * @param $albumId ID of the album
	 * @param $pictureId ID of the picture
	 * @return URL to the page that removes the picture
	 */
	public function generateRemovePictureURL(int $id, string $albumId, string $pictureId): string;

	/**
	 * Generates a URL that an album form redirects to (that is reponsible for inserting or updating an album)
	 *
	 * @param $albumId ID of the album to update. null indicates that a new album must created
	 * @return The URL where the form should redirect to
	 */
	public function generateAlbumFormURL(?string $albumId): ?string;

	/**
	 * Generates a URL that the pictures uploader form redirects to.
	 *
	 * @param $albumId ID of the album to update
	 * @return The URL the pictures uploader form redirects to
	 */
	public function generatePicturesUploaderFormURL(string $albumId): string;
}
?>
