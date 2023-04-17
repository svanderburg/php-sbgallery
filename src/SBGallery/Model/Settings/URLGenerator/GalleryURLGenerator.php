<?php
namespace SBGallery\Model\Settings\URLGenerator;

/**
 * Specifies a number of methods that generate URLs to pages that manage the content of a gallery.
 */
interface GalleryURLGenerator extends AlbumURLGenerator
{
	/**
	 * Generates a URL to a page that displays an album from the gallery
	 *
	 * @param $albumId ID of the album
	 * @return URL to the album with the given ID
	 */
	public function generateAlbumURL(string $albumId): string;

	/**
	 * Generates a URL that allows a user to add an album from the gallery
	 *
	 * @return URL to the add album page
	 */
	public function generateAddAlbumURL(): string;

	/**
	 * Generates a URL to a page that removes an album from the gallery
	 *
	 * @param $id Anchor ID of the album in the gallery so that the user gets redirected back to the right scroll position
	 * @param $albumId ID of the album
	 * @return URL to the page that removes the album
	 */
	public function generateRemoveAlbumURL(int $id, string $albumId): string;

	/**
	 * Generates a URL to a page that moves an album left in the gallery.
	 *
	 * @param $id Anchor ID of the album in the gallery so that the user gets redirected back to the right scroll position
	 * @param $albumId ID of the album
	 * @return URL to the page that moves the album
	 */
	public function generateMoveAlbumLeftURL(int $id, string $albumId): string;

	/**
	 * Generates a URL to a page that moves an album right in the gallery.
	 *
	 * @param $id Anchor ID of the album in the gallery so that the user gets redirected back to the right scroll position
	 * @param $albumId ID of the album
	 * @return URL to the page that moves the album
	 */
	public function generateMoveAlbumRightURL(int $id, string $albumId): string;
}
?>
