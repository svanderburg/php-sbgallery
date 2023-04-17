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

	private PDOStatement $stmt;

	private $row;

	public function __construct(PDO $dbh, string $albumId, AlbumSettings $settings)
	{
		$this->dbh = $dbh;
		$this->albumId = $albumId;
		$this->settings = $settings;
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

	public function rewind()
	{
		$this->stmt = PictureEntity::queryAll($this->dbh, $this->albumId, $this->settings->picturesTable);
		$this->row = $this->stmt->fetch();
	}

	public function valid(): bool
	{
		return ($this->row !== false);
	}
}
?>
