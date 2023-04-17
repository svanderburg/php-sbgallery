<?php
namespace SBGallery\Model;
use PDO;
use SBGallery\Model\Entity\AlbumEntity;
use SBGallery\Model\Exception\AlbumNotFoundException;
use SBGallery\Model\FileSet\AlbumFileSet;
use SBGallery\Model\Iterator\AlbumThumbnailIterator;
use SBGallery\Model\Settings\GallerySettings;

/**
 * Representation of a configurable gallery whose state can be modified.
 */
class Gallery
{
	/** Database connection to the gallery database */
	public PDO $dbh;

	/** Object that contains gallery settings */
	public GallerySettings $settings;

	/**
	 * Constructs a new gallery instance.
	 *
	 * @param $dbh Database connection to the gallery database
	 * @param $settings Object that contains gallery settings
	 */
	public function __construct(PDO $dbh, GallerySettings $settings)
	{
		$this->dbh = $dbh;
		$this->settings = $settings;
	}

	/**
	 * Checks whether a given album is empty.
	 *
	 * @param $albumId ID of the album
	 * @return true if the albums contains is empty, else false
	 */
	public function albumIsEmpty(string $albumId): bool
	{
		$stmt = AlbumEntity::queryPictureCount($this->dbh, $albumId, $this->settings->picturesTable);
		if(($row = $stmt->fetch()) !== false)
			return ($row["count(*)"] == 0);
		else
			return false;
	}

	/**
	 * Creates a new empty album that inherits the settings of the gallery
	 *
	 * @param $albumId ID of the album
	 * @return A new empty album
	 */
	public function newAlbum(string $albumId = null): Album
	{
		return new Album($this->dbh, $this->settings->constructAlbumSettings(), $albumId);
	}

	/**
	 * Queries an album from the gallery
	 *
	 * @param $albumId ID of the album
	 * @return The requested album
	 * @throws AlbumNotFoundException If the album does not exist
	 */
	public function queryAlbum(string $albumId): Album
	{
		$stmt = AlbumEntity::queryOne($this->dbh, $albumId, $this->settings->albumsTable);

		if(($row = $stmt->fetch()) === false)
			throw new AlbumNotFoundException($this->settings->galleryLabels->cannotFindAlbum.$albumId);
		else
		{
			$album = $this->newAlbum($albumId);
			$album->importValues($row);
			return $album;
		}
	}

	/**
	 * Inserts an album into the database.
	 *
	 * @param $album Album to insert
	 */
	public function insertAlbum(Album $album): void
	{
		$row = $album->exportValues();
		AlbumEntity::insert($this->dbh, $row, $this->settings->albumsTable, $this->settings->thumbnailsTable);
		AlbumFileSet::createAlbumDirectories($this->settings->baseDir, $album->form->fields["ALBUM_ID"]->exportValue(), $this->settings->dirPermissions);
	}

	/**
	 * Updates an album in the database
	 *
	 * @param $albumId ID of the album to update
	 * @param $album Album object containing new properties
	 */
	public function updateAlbum(string $albumId, Album $album): void
	{
		$row = $album->exportValues();
		AlbumEntity::update($this->dbh, $row, $albumId, $this->settings->albumsTable);
		AlbumFileSet::renameAlbumDirectory($this->settings->baseDir, $albumId, $album->form->fields["ALBUM_ID"]->exportValue());
	}

	/**
	 * Removes an album from the database.
	 *
	 * @param $albumId ID of the album to remove
	 */
	public function removeAlbum(string $albumId): void
	{
		AlbumEntity::remove($this->dbh, $albumId, $this->settings->albumsTable);
		AlbumFileSet::removeAlbumDirectories($this->settings->baseDir, $albumId);
	}

	/**
	 * Moves an album left in the gallery
	 *
	 * @param $albumId ID of the album to move
	 * @return true if the album was moved, false if it was not
	 */
	public function moveLeftAlbum(string $albumId): bool
	{
		return AlbumEntity::moveLeft($this->dbh, $albumId, $this->settings->albumsTable);
	}

	/**
	 * Moves an album right in the gallery
	 *
	 * @param $albumId ID of the album to move
	 * @return true if the album was moved, false if it was not
	 */
	public function moveRightAlbum(string $albumId): bool
	{
		return AlbumEntity::moveRight($this->dbh, $albumId, $this->settings->albumsTable);
	}

	/**
	 * Provides an iterator that steps over the thumbnails of each album.
	 *
	 * @param $displayOnlyVisible Specifies whether to include only visible albums
	 * @return Gallery item iterator that iterates over all available gallery items
	 */
	public function albumThumbnailIterator(bool $displayOnlyVisible): AlbumThumbnailIterator
	{
		return new AlbumThumbnailIterator($this->dbh, $displayOnlyVisible, $this->settings);
	}
}
?>
