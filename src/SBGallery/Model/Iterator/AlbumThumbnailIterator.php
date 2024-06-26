<?php
namespace SBGallery\Model\Iterator;

use PDO;
use PDOStatement;
use Iterator;
use SBGallery\Model\AlbumThumbnail;
use SBGallery\Model\Entity\AlbumEntity;
use SBGallery\Model\Settings\GallerySettings;

/**
 * Iterator that steps over each album thumbnail in a gallery
 */
class AlbumThumbnailIterator implements Iterator
{
	private PDO $dbh;

	private bool $displayOnlyVisible;

	private GallerySettings $settings;

	private int $page;

	private PDOStatement $stmt;

	private $row;

	public function __construct(PDO $dbh, bool $displayOnlyVisible, GallerySettings $settings, int $page)
	{
		$this->dbh = $dbh;
		$this->displayOnlyVisible = $displayOnlyVisible;
		$this->settings = $settings;
		$this->page = $page;
	}

	public function current(): mixed
	{
		return new AlbumThumbnail($this->row["ALBUM_ID"], $this->row["PICTURE_ID"], $this->row["Title"], $this->row["FileType"]);
	}

	public function key(): mixed
	{
		return $this->row["ALBUM_ID"];
	}

	public function next(): void
	{
		$this->row = $this->stmt->fetch();
	}

	public function rewind(): void
	{
		if($this->settings->galleryPageSize === null)
			$this->stmt = AlbumEntity::queryThumbnails($this->dbh, $this->displayOnlyVisible, $this->settings->albumsTable, $this->settings->thumbnailsTable, $this->settings->picturesTable);
		else
			$this->stmt = AlbumEntity::queryThumbnailPage($this->dbh, $this->displayOnlyVisible, $this->page, $this->settings->galleryPageSize, $this->settings->albumsTable, $this->settings->thumbnailsTable, $this->settings->picturesTable);

		$this->row = $this->stmt->fetch();
	}

	public function valid(): bool
	{
		return ($this->row !== false);
	}
}
?>
