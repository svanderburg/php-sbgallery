<?php
namespace SBGallery\Model\Page;
use Iterator;
use SBGallery\Model\Page\Iterator\AlbumPageIterator;

abstract class TraversableGalleryPage extends GalleryPage
{
	public function subPageIterator(): Iterator
	{
		return new AlbumPageIterator($this);
	}
}
?>
