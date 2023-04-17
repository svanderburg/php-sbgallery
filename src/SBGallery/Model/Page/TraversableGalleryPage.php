<?php
namespace SBGallery\Model\Page;
use SBGallery\Model\Page\Iterator\AlbumPageIterator;

/**
 * Extends the gallery page with a sub page iterator so that albums can be
 * browsed from the menu sections.
 */
class TraversableGalleryPage extends GalleryPage
{
	/**
	 * @see Page#subPageIterator()
	 */
	public function subPageIterator(): AlbumPageIterator
	{
		return new AlbumPageIterator($this->dbh, !$this->checker->checkWritePermissions(), $this);
	}
}
?>
