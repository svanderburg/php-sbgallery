<?php
require_once("entities/AlbumEntity.class.php");
require_once("entities/PictureEntity.class.php");
require_once("form/AlbumForm.class.php");
require_once("filesets/AlbumFileSet.class.php");
require_once("filesets/PictureFileSet.class.php");
require_once("Picture.class.php");

/**
 * A representation of an album whose state can be modified.
 */
class Album
{
	private static $labels = array(
		"ALBUM_ID" => "Id",
		"Title" => "Title",
		"Visible" => "Visible",
		"Description" => "Description",
		"Add picture" => "Add picture",
		"Add multiple pictures" => "Add multiple pictures",
		"Move left" => "Move left",
		"Move right" => "Move right",
		"Set as album thumbnail" => "Set as album thumbnail",
		"Remove" => "Remove"
	);

	/** Database connection handler */
	public $dbh;

	/** Base URL of the gallery */
	public $baseURL;

	/** URL to the page that displays an individual picture */
	public $pictureDisplayURL;

	/** URL to the page allowing one to upload multiple pictures */
	public $addMultiplePicturesURL;

	/** URL to the base directory of the icons of the album */
	public $iconsPath;

	/** Base directory where all album artifacts are stored */
	public $baseDir;

	/** Maximum width of a thumbnail image */
	public $thumbnailWidth;

	/** Maximum height of a thumbnail image */
	public $thumbnailHeight;

	/** Maximum width of a picture */
	public $pictureWidth;

	/** Maximum height of a picture */
	public $pictureHeight;

	/** Message labels for translation of the album properties */
	public $albumLabels;

	/** Message labels for translation of the picture properties */
	public $pictureLabels;

	/** Configuration settings for the embedded editor */
	public $editorSettings;

	/** The file permissions for the files stored in the base dir */
	public $filePermissions;

	/** Stores the properties of an individual album */
	public $entity = false;

	/** Form that can be used to validate and display an album's properties */
	public $form;

	/** Name of the database table storing album properties */
	private $albumsTable;

	/** Name of the database table storing thumbnail properties */
	private $thumbnailsTable;

	/** Name of the database table storing picture properties */
	private $picturesTable;

	/**
	 * Constructs a new album instance.
	 *
	 * @param PDO $dbh Database connection handler
	 * @param string $baseURL Base URL of the gallery
	 * @param string $pictureDisplayURL URL to the page that displays an individual picture
	 * @param string $addMultiplePicturesURL URL to the page allowing one to upload multiple pictures
	 * @param string $iconsPath URL to the base directory of the icons of the album
	 * @param string $baseDir Base directory where all album artifacts are stored
	 * @param int $thumbnailWidth Maximum width of a thumbail image
	 * @param int $thumbnailHeight Maximum height of a thumbnail image
	 * @param int $pictureWidth Maximum width of a picture
	 * @param int $picutreHeight Maximum height of a picture
	 * @param array $albumLabels Message labels for translation of the album properties
	 * @param array $pictureLabels Message labels for translation of the picture properties
	 * @param array $editorSettings Configuration settings for the embedded editor
	 * @param array $filePermissions The file permissions for the files stored in the base dir
	 * @param string $albumsTable Name of the database table storing album properties
	 * @param string $thumbnailsTable Name of the database table storing thumbnail properties
	 * @param string $picturesTable Name of the database table storing picture properties
	 */
	public function __construct(PDO $dbh, $baseURL, $pictureDisplayURL, $addMultiplePicturesURL, $iconsPath, $baseDir, $thumbnailWidth, $thumbnailHeight, $pictureWidth, $pictureHeight, array $albumLabels = null, array $pictureLabels = null, array $editorSettings = null, $filePermissions = 0777, $albumsTable = "albums", $thumbnailsTable = "thumbnails", $picturesTable = "pictures")
	{
		$this->dbh = $dbh;
		$this->baseURL = $baseURL;
		$this->pictureDisplayURL = $pictureDisplayURL;
		$this->addMultiplePicturesURL = $addMultiplePicturesURL;
		$this->iconsPath = $iconsPath;
		$this->baseDir = $baseDir;
		$this->thumbnailWidth = $thumbnailWidth;
		$this->thumbnailHeight = $thumbnailHeight;
		$this->pictureWidth = $pictureWidth;
		$this->pictureHeight = $pictureHeight;
		if($albumLabels === null)
			$this->albumLabels = Album::$labels;
		else
			$this->albumLabels = $albumLabels;
		$this->pictureLabels = $pictureLabels;
		$this->editorSettings = $editorSettings;
		$this->filePermissions = $filePermissions;
		$this->albumsTable = $albumsTable;
		$this->thumbnailsTable = $thumbnailsTable;
		$this->picturesTable = $picturesTable;
	}

	/**
	 * Fetches a requested album from the database
	 *
	 * @param string $albumId ID of the album
	 */
	private function fetchEntity($albumId)
	{
		$stmt = AlbumEntity::queryOne($this->dbh, $albumId, $this->albumsTable);
		$this->entity = $stmt->fetch();
	}

	/**
	 * Constructs a form that can be used to validate and display an album's
	 * properties.
	 *
	 * @param bool $updateMode Whether the form should be used for updating an existing album
	 */
	private function constructForm($updateMode)
	{
		$this->form = new AlbumForm($updateMode, $this->albumLabels, $this->editorSettings);
	}

	/**
	 * Constructs a picture with identical configuration settings as this
	 * album.
	 *
	 * @param string $albumId ID of the album
	 * @return Picture A picture with the same configuration properties
	 */
	public function constructPicture($albumId)
	{
		return new Picture($this->dbh, $this->baseURL."/".$albumId, $this->baseDir."/".$albumId, $this->pictureDisplayURL, $this->iconsPath, $this->thumbnailWidth, $this->thumbnailHeight, $this->pictureWidth, $this->pictureHeight, $this->pictureLabels, $this->editorSettings, $this->filePermissions, $this->picturesTable, $this->thumbnailsTable, $this->albumsTable);
	}

	/**
	 * Queries all pictures belonging to this album.
	 *
	 * @return PDOStatement A PDO statement that can be used to retrieve picture properties
	 */
	public function queryPictures()
	{
		return PictureEntity::queryAll($this->dbh, $this->entity["ALBUM_ID"], $this->picturesTable);
	}

	/**
	 * Modifies the state to support the creation of a new album.
	 */
	public function create()
	{
		$this->constructForm(false);
		$this->form->fields["__operation"]->value = "insert_album";
	}

	/**
	 * Modifies the state to view a particular album.
	 *
	 * @param string $albumId ID of the album
	 */
	public function view($albumId)
	{
		$this->constructForm(true);
		$this->fetchEntity($albumId);

		if($this->entity === false)
			throw new Exception(array_key_exists("cannotFindAlbum", $this->albumLabels) ? $this->albumLabels["cannotFindAlbum"] : "Cannot find requested album!");
		else
		{
			$this->form->importValues($this->entity);
			$this->form->fields["__operation"]->value = "update_album";
			$this->form->fields["old_ALBUM_ID"]->value = $this->entity["ALBUM_ID"];
		}
	}

	/**
	 * Inserts a given album into the database and uploads the files into
	 * the gallery base dir.
	 *
	 * @param array $album Array with properties of an album.
	 */
	public function insert(array $album)
	{
		$this->constructForm(false);
		$this->form->importValues($album);
		$this->form->checkFields();
		
		if($this->form->checkValid())
		{
			$this->entity = $this->form->exportValues();
			AlbumEntity::insert($this->dbh, $this->entity, $this->albumsTable, $this->thumbnailsTable);
			AlbumFileSet::createAlbumDirectories($this->baseDir, $this->entity["ALBUM_ID"], $this->filePermissions);
			return true;
		}
		else
			return false;
	}

	/**
	 * Updates an existing album in the database and the filesystem.
	 *
	 * @param array $album Array with properties of an album.
	 */
	public function update(array $album)
	{
		$this->constructForm(true);
		$this->form->importValues($album);
		$this->form->checkFields();

		if($this->form->checkValid())
		{
			$this->entity = $this->form->exportValues();
			AlbumEntity::update($this->dbh, $this->entity, $this->entity["old_ALBUM_ID"], $this->albumsTable);
			AlbumFileSet::renameAlbumDirectory($this->baseDir, $this->entity["old_ALBUM_ID"], $this->entity["ALBUM_ID"]);
			return true;
		}
		else
			return false;
	}

	/**
	 * Removes an album from the database and the filesystem.
	 *
	 * @param string $albumId ID of the album
	 */
	public function remove($albumId)
	{
		AlbumEntity::remove($this->dbh, $albumId, $this->albumsTable);
		AlbumFileSet::removeAlbumDirectories($this->baseDir, $albumId);
	}

	/**
	 * Bulk inserts multiple pictures into the database.
	 *
	 * @param string $albumId ID of the album
	 * @param string $key Key of the field that uploads the file
	 */
	public function insertMultiplePictures($albumId, $key)
	{
		foreach($_FILES[$key]["name"] as $i => $name)
		{
			/* Compose picture entity object */
			$title = pathinfo($name, PATHINFO_FILENAME);
			
			$picture = array(
				"PICTURE_ID" => $title,
				"Title" => $title,
				"Description" => "",
				"FileType" => PictureFileSet::determineImageType($_FILES[$key]["type"][$i]),
				"ALBUM_ID" => $albumId
			);

			PictureEntity::insert($this->dbh, $picture, $this->picturesTable); /* Insert picture record into the database */
			PictureFileSet::generatePictures($_FILES[$key]["tmp_name"][$i], $this->baseDir."/".$albumId, $picture["PICTURE_ID"], $picture["FileType"], $this->thumbnailWidth, $this->thumbnailHeight, $this->pictureWidth, $this->pictureHeight, $this->filePermissions);
		}
	}

	/**
	 * Moves the album left in the overview of albums
	 *
	 * @param string $albumId ID of the album
	 */
	public function moveLeft($albumId)
	{
		AlbumEntity::moveLeft($this->dbh, $albumId, $this->albumsTable);
	}

	/**
	 * Moves the album right in the overview of albums
	 *
	 * @param string $albumId ID of the album
	 */
	public function moveRight($albumId)
	{
		AlbumEntity::moveRight($this->dbh, $albumId, $this->albumsTable);
	}
}
?>
