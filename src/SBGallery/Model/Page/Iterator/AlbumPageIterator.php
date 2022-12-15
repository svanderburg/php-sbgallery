<?php
namespace SBGallery\Model\Page\Iterator;
use PDOStatement;
use Iterator;
use SBGallery\Model\Page\GalleryPage;

class AlbumPageIterator implements Iterator
{
	public GalleryPage $galleryPage;

	public PDOStatement $stmt;

	public $row;

	public bool $reachedEnd;

	public bool $authenticated;

	public function __construct(GalleryPage $galleryPage)
	{
		$this->galleryPage = $galleryPage;
		$this->stmt = $this->galleryPage->gallery->queryAlbums(false);
		$this->row = $this->stmt->fetch();
		$this->reachedEnd = false;

		$checker = $galleryPage->constructGalleryPermissionChecker();
		$this->authenticated = $checker->checkWritePermissions();
	}

	public function current(): mixed
	{
		if($this->row === false)
			return $this->galleryPage->crudPageManager->operationPages["create_album"];
		else
			return $this->galleryPage->createDetailPage(array("albumId" => $this->row["ALBUM_ID"]));
	}

	public function key(): mixed
	{
		if($this->row === false)
			return "create_album";
		else
			return $this->row["ALBUM_ID"];
	}

	public function next(): void
	{
		if($this->row === false)
			$this->reachedEnd = true;
		else
			$this->row = $this->stmt->fetch();
	}

	public function rewind(): void
	{
		// Do nothing
	}

	public function valid(): bool
	{
		return ($this->authenticated && !$this->reachedEnd) || (!$this->authenticated && $this->row !== false);
	}
}
?>
