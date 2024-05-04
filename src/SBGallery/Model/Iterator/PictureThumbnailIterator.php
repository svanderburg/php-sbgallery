<?php
namespace SBGallery\Model\Iterator;
use PDO;
use PDOStatement;
use Iterator;
use SBGallery\Model\PictureThumbnail;
use SBGallery\Model\Entity\PictureEntity;
use SBGallery\Model\Settings\AlbumSettings;

/**
 * Iterator that steps over each picture thumbnail in an album
 */
class PictureThumbnailIterator implements Iterator
{
	private PDO $dbh;

	private string $albumId;

	private AlbumSettings $settings;

	private int $page;

	private PDOStatement $stmt;

	private $row;

	public function __construct(PDO $dbh, string $albumId, AlbumSettings $settings, int $page)
	{
		$this->dbh = $dbh;
		$this->albumId = $albumId;
		$this->settings = $settings;
		$this->page = $page;
	}

	public function current(): mixed
	{
		return new PictureThumbnail($this->row["PICTURE_ID"], $this->row["Title"], $this->row["FileType"], $this->row["ALBUM_ID"]);
	}

	public function key(): mixed
	{
		return $this->row["PICTURE_ID"];
	}

	public function next(): void
	{
		$this->row = $this->stmt->fetch();
	}

	public function rewind(): void
	{
		if($this->settings->pageSize === null)
			$this->stmt = PictureEntity::queryAll($this->dbh, $this->albumId, $this->settings->picturesTable);
		else
			$this->stmt = PictureEntity::queryPage($this->dbh, $this->albumId, $this->page, $this->settings->pageSize, $this->settings->picturesTable);

		$this->row = $this->stmt->fetch();
	}

	public function valid(): bool
	{
		return ($this->row !== false);
	}
}
?>
