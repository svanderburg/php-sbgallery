<?php
namespace SBGallery\Model;

/**
 * Provides a facility that can be used to check whether a user has write
 * permissions to a gallery.
 */
interface GalleryPermissionChecker
{
	/**
	 * Checks whether the user has the permissions to modify the album.
	 *
	 * @return true if the user has permissions, else false
	 */
	public function checkWritePermissions(): bool;
}
?>
