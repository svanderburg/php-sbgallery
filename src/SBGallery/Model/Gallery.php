<?php
namespace SBGallery\Model;
use PDO;
use PDOStatement;
use SBGallery\Model\Entity\AlbumEntity;

/**
 * A representation of a gallery whose state can be modified.
 */
class Gallery
{
	private static array $referenceLabels = array(
		"Gallery" => "Gallery",
		"Add album" => "Add album",
		"Move left" => "Move left",
		"Move right" => "Move right",
		"Remove" => "Remove"
	);

	/** Database connection handler */
	public PDO $dbh;

	/** Base URL of the gallery */
	public string $baseURL;

	/** URL to the page that displays an individual album */
	public string $albumDisplayURL;

	/** URL to the page that displays an individual picture */
	public string $pictureDisplayURL;

	/** URL to the page allowing one to upload multiple pictures */
	public string $addMultiplePicturesURL;

	/** URL to the base directory of the icons of the album */
	public string $iconsPath;

	/** Base directory where all album artifacts are stored */
	public string $baseDir;

	/** The directory permissions for the files stored in the base dir */
	public int $dirPermissions;

	/** The file permissions for the files stored in the base dir */
	public int $filePermissions;

	/** Maximum width of a thumbnail image */
	public int $thumbnailWidth;

	/** Maximum height of a thumbnail image */
	public int $thumbnailHeight;

	/** Maximum width of a picture */
	public int $pictureWidth;

	/** Maximum height of a picture */
	public int $pictureHeight;

	/** Message labels for translation of the gallery properties */
	public array $galleryLabels;

	/** Message labels for translation of the album properties */
	public ?array $albumLabels;

	/** Message labels for translation of the picture properties */
	public ?array $pictureLabels;

	/** Configuration settings for the embedded editor */
	public ?array $editorSettings;

	/** Whether to display anchors to support redirects to albums and pictures */
	public bool $displayAnchors;

	/** Name of the database table storing album properties */
	public string $albumsTable;

	/** Name of the database table storing thumbnail properties */
	public string $thumbnailsTable;

	/** Name of the database table storing picture properties */
	public string $picturesTable;

	/**
	 * Creates a new gallery object.
	 *
	 * @param $dbh Database connection handler
	 * @param $baseURL Base URL of the gallery
	 * @param $albumDisplayURL URL to the page that displays an individual album
	 * @param $pictureDisplayURL URL to the page that displays an individual picture
	 * @param $addMultiplePicturesURL URL to the page allowing one to upload multiple pictures
	 * @param $iconsPath URL to the base directory of the icons of the album
	 * @param $baseDir Base directory where all album artifacts are stored
	 * @param $thumbnailWidth Maximum width of a thumbail image
	 * @param $thumbnailHeight Maximum height of a thumbnail image
	 * @param $pictureWidth Maximum width of a picture
	 * @param $pictureHeight Maximum height of a picture
	 * @param $galleryLabels Message labels for translation of the gallery properties
	 * @param $albumLabels Message labels for translation of the album properties
	 * @param $pictureLabels Message labels for translation of the picture properties
	 * @param $editorSettings Configuration settings for the embedded editor
	 * @param $displayAnchors Whether to display anchors to support redirects to albums and pictures
	 * @param $dirPermissions The directory permissions for the files stored in the base dir
	 * @param $filePermissions The file permissions for the files stored in the base dir
	 * @param $albumsTable Name of the database table storing album properties
	 * @param $thumbnailsTable Name of the database table storing thumbnail properties
	 * @param $picturesTable Name of the database table storing picture properties
	 */
	public function __construct(PDO $dbh, string $baseURL, string $albumDisplayURL, string $pictureDisplayURL, string $addMultiplePicturesURL, string $iconsPath, string $baseDir, int $thumbnailWidth, int $thumbnailHeight, int $pictureWidth, int $pictureHeight, array $galleryLabels = null, array $albumLabels = null, array $pictureLabels = null, array $editorSettings = null, bool $displayAnchors = true, int $dirPermissions = 0777, int $filePermissions = 0666, string $albumsTable = "albums", string $thumbnailsTable = "thumbnails", string $picturesTable = "pictures")
	{
		$this->dbh = $dbh;
		$this->baseURL = $baseURL;
		$this->albumDisplayURL = $albumDisplayURL;
		$this->pictureDisplayURL = $pictureDisplayURL;
		$this->addMultiplePicturesURL = $addMultiplePicturesURL;
		$this->iconsPath = $iconsPath;
		$this->baseDir = $baseDir;
		$this->thumbnailWidth = $thumbnailWidth;
		$this->thumbnailHeight = $thumbnailHeight;
		$this->pictureWidth = $pictureWidth;
		$this->pictureHeight = $pictureHeight;
		if($galleryLabels === null)
			$this->galleryLabels = Gallery::$referenceLabels;
		else
			$this->galleryLabels = $galleryLabels;
		$this->albumLabels = $albumLabels;
		$this->pictureLabels = $pictureLabels;
		$this->editorSettings = $editorSettings;
		$this->displayAnchors = $displayAnchors;
		$this->dirPermissions = $dirPermissions;
		$this->filePermissions = $filePermissions;
		$this->albumsTable = $albumsTable;
		$this->thumbnailsTable = $thumbnailsTable;
		$this->picturesTable = $picturesTable;
	}

	/**
	 * Constructs an album object with the same configuration properties as
	 * this gallery.
	 *
	 * @return Album with the same configuration properties
	 */
	public function constructAlbum(): Album
	{
		return new Album($this->dbh, $this->baseURL, $this->pictureDisplayURL, $this->addMultiplePicturesURL, $this->iconsPath, $this->baseDir, $this->thumbnailWidth, $this->thumbnailHeight, $this->pictureWidth, $this->pictureHeight, $this->albumLabels, $this->pictureLabels, $this->editorSettings, $this->displayAnchors, $this->dirPermissions, $this->filePermissions, $this->albumsTable, $this->thumbnailsTable, $this->picturesTable);
	}

	/**
	 * Querie the properties of all albums to be displayed.
	 *
	 * @param $displayOnlyVisible Indicates whether only visible albums are displayed
	 * @return PDO statement that can be used to retrieve the album records
	 */
	public function queryAlbums(bool $displayOnlyVisible): PDOStatement
	{
		return AlbumEntity::queryThumbnails($this->dbh, $displayOnlyVisible, $this->albumsTable);
	}

	/**
	 * Determines the amount pictures in a given album.
	 *
	 * @param $albumId ID of the album
	 * @return PDO statement that can be used to retrieve the amount of pictures in the album
	 */
	public function queryPictureCount(string $albumId): PDOStatement
	{
		return AlbumEntity::queryPictureCount($this->dbh, $albumId, $this->picturesTable);
	}
}
?>
