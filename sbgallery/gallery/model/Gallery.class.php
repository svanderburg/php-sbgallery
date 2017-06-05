<?php
require_once("entities/AlbumEntity.class.php");
require_once("Album.class.php");

/**
 * A representation of a gallery whose state can be modified.
 */
class Gallery
{
	private static $referenceLabels = array(
		"Add album" => "Add album",
		"Move left" => "Move left",
		"Move right" => "Move right",
		"Remove" => "Remove"
	);

	/** Database connection handler */
	public $dbh;

	/** Base URL of the gallery */
	public $baseURL;

	/** URL to the page that displays an individual album */
	public $albumDisplayURL;

	/** URL to the page that displays an individual picture */
	public $pictureDisplayURL;

	/** URL to the page allowing one to upload multiple pictures */
	public $addMultiplePicturesURL;

	/** URL to the base directory of the icons of the album */
	public $iconsPath;

	/** Base directory where all album artifacts are stored */
	public $baseDir;

	/** The file permissions for the files stored in the base dir */
	public $filePermissions;

	/** Maximum width of a thumbnail image */
	public $thumbnailWidth;

	/** Maximum height of a thumbnail image */
	public $thumbnailHeight;

	/** Maximum width of a picture */
	public $pictureWidth;

	/** Maximum height of a picture */
	public $pictureHeight;

	/** Message labels for translation of the gallery properties */
	public $galleryLabels;

	/** Message labels for translation of the album properties */
	public $albumLabels;

	/** Message labels for translation of the picture properties */
	public $pictureLabels;

	/** Configuration settings for the embedded editor */
	public $editorSettings;

	/** Name of the database table storing album properties */
	private $albumsTable;

	/** Name of the database table storing thumbnail properties */
	private $thumbnailsTable;

	/** Name of the database table storing picture properties */
	private $picturesTable;

	/**
	 * Creates a new gallery object.
	 *
	 * @param PDO $dbh Database connection handler
	 * @param string $baseURL Base URL of the gallery
	 * @param string $albumDisplayURL URL to the page that displays an individual album
	 * @param string $pictureDisplayURL URL to the page that displays an individual picture
	 * @param string $addMultiplePicturesURL URL to the page allowing one to upload multiple pictures
	 * @param string $iconsPath URL to the base directory of the icons of the album
	 * @param string $baseDir Base directory where all album artifacts are stored
	 * @param int $thumbnailWidth Maximum width of a thumbail image
	 * @param int $thumbnailHeight Maximum height of a thumbnail image
	 * @param int $pictureWidth Maximum width of a picture
	 * @param int $pictureHeight Maximum height of a picture
	 * @param array $galleryLabels Message labels for translation of the gallery properties
	 * @param array $albumsLabels Message labels for translation of the album properties
	 * @param array $pictureLabels Message labels for translation of the picture properties
	 * @param array $editorSettings Configuration settings for the embedded editor
	 * @param int $filePermissions The file permissions for the files stored in the base dir
	 * @param string $albumsTable Name of the database table storing album properties
	 * @param string $thumbnailsTable Name of the database table storing thumbnail properties
	 * @param string $picturesTable Name of the database table storing picture properties
	 */
	public function __construct(PDO $dbh, $baseURL, $albumDisplayURL, $pictureDisplayURL, $addMultiplePicturesURL, $iconsPath, $baseDir, $thumbnailWidth, $thumbnailHeight, $pictureWidth, $pictureHeight, array $galleryLabels = null, array $albumLabels = null, array $pictureLabels = null, array $editorSettings = null, $filePermissions = 0777, $albumsTable = "albums", $thumbnailsTable = "thumbnails", $picturesTable = "pictures")
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
		$this->filePermissions = $filePermissions;
		$this->albumsTable = $albumsTable;
		$this->thumbnailsTable = $thumbnailsTable;
		$this->picturesTable = $picturesTable;
	}

	/**
	 * Constructs an album object with the same configuration properties as
	 * this gallery.
	 *
	 * @return Album Album with the same configuration properties
	 */
	public function constructAlbum()
	{
		return new Album($this->dbh, $this->baseURL, $this->pictureDisplayURL, $this->addMultiplePicturesURL, $this->iconsPath, $this->baseDir, $this->thumbnailWidth, $this->thumbnailHeight, $this->pictureWidth, $this->pictureHeight, $this->albumLabels, $this->pictureLabels, $this->editorSettings, $this->filePermissions, $this->albumsTable, $this->thumbnailsTable, $this->picturesTable);
	}

	/**
	 * Querie the properties of all albums to be displayed.
	 *
	 * @param bool $displayOnlyVisible Indicates whether only visible albums are displayed
	 * @return PDOStatement A PDO statement that can be used to retrieve the album records
	 */
	public function queryAlbums($displayOnlyVisible)
	{
		return AlbumEntity::queryThumbnails($this->dbh, $displayOnlyVisible, $this->albumsTable);
	}

	/**
	 * Determines the amount pictures in a given album.
	 *
	 * @param string $albumId ID of the album
	 * @return int The amount of pictures in the album
	 */
	public function queryPictureCount($albumId)
	{
		return AlbumEntity::queryPictureCount($this->dbh, $albumId, $this->picturesTable);
	}
}
?>
