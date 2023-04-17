<?php
namespace SBGallery\Model\Page\Iterator;
use PDO;
use PDOStatement;
use Iterator;
use SBGallery\Model\Gallery;
use SBGallery\Model\GalleryPermissionChecker;
use SBGallery\Model\Entity\AlbumEntity;
use SBGallery\Model\Page\GalleryPage;
use SBGallery\Model\Page\AlbumPage;

/**
 * Iterator that steps over each album page that a gallery page contains
 */
class AlbumPageIterator implements Iterator
{
	private PDO $dbh;

	private bool $displayOnlyVisible;

	private GalleryPage $galleryPage;

	private PDOStatement $stmt;

	private $row;

	private bool $reachedEnd;

	private bool $authenticated;

	public function __construct(PDO $dbh, bool $displayOnlyVisible, GalleryPage $galleryPage)
	{
		$this->dbh = $dbh;
		$this->displayOnlyVisible = $displayOnlyVisible;
		$this->galleryPage = $galleryPage;
		$this->authenticated = $galleryPage->checker->checkWritePermissions();
	}

	public function current(): mixed
	{
		if($this->row === false)
			return $this->galleryPage->crudPageManager->operationPages["create_album"];
		else
			return new AlbumPage($this->galleryPage->gallery, $this->row["ALBUM_ID"], $this->galleryPage->settings, $this->galleryPage->checker, $this->galleryPage->albumContents);
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

	public function rewind()
	{
		$this->stmt = AlbumEntity::queryThumbnails($this->dbh, $this->displayOnlyVisible, $this->galleryPage->gallery->settings->albumsTable, $this->galleryPage->gallery->settings->thumbnailsTable, $this->galleryPage->gallery->settings->picturesTable);
		$this->row = $this->stmt->fetch();
		$this->reachedEnd = false;
	}

	public function valid(): bool
	{
		return ($this->authenticated && !$this->reachedEnd) || (!$this->authenticated && $this->row !== false);
	}
}
?>
