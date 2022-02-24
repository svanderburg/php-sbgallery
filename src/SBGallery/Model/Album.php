<?php
namespace SBGallery\Model;
use Exception;
use PDO;
use PDOStatement;
use SBGallery\Model\Form\AlbumForm;
use SBGallery\Model\Entity\AlbumEntity;
use SBGallery\Model\Entity\PictureEntity;
use SBGallery\Model\FileSet\AlbumFileSet;
use SBGallery\Model\FileSet\PictureFileSet;

/**
 * A representation of an album whose state can be modified.
 */
class Album
{
	private static array $labels = array(
		"Gallery" => "Gallery",
		"ALBUM_ID" => "Id",
		"Title" => "Title",
		"Visible" => "Visible",
		"Description" => "Description",
		"Add picture" => "Add picture",
		"Add multiple pictures" => "Add multiple pictures",
		"Move left" => "Move left",
		"Move right" => "Move right",
		"Set as album thumbnail" => "Set as album thumbnail",
		"Remove" => "Remove",
		"Submit" => "Submit",
		"Form invalid" => "One or more fields are incorrectly specified and marked with a red color!",
		"Field invalid" => "This field is incorrectly specified!"
	);

	/** Database connection handler */
	public PDO $dbh;

	/** Base URL of the gallery */
	public string $baseURL;

	/** URL to the page that displays an individual picture */
	public string $pictureDisplayURL;

	/** URL to the page allowing one to upload multiple pictures */
	public string $addMultiplePicturesURL;

	/** URL to the base directory of the icons of the album */
	public string $iconsPath;

	/** Base directory where all album artifacts are stored */
	public string $baseDir;

	/** Maximum width of a thumbnail image */
	public int $thumbnailWidth;

	/** Maximum height of a thumbnail image */
	public int $thumbnailHeight;

	/** Maximum width of a picture */
	public string $pictureWidth;

	/** Maximum height of a picture */
	public string $pictureHeight;

	/** Message labels for translation of the album properties */
	public array $albumLabels;

	/** Message labels for translation of the picture properties */
	public ?array $pictureLabels;

	/** Configuration settings for the embedded editor */
	public ?array $editorSettings;

	/** Whether to display anchors to support redirects to albums and pictures */
	public bool $displayAnchors;

	/** The file permissions for the directories stored in the base dir */
	public int $dirPermissions;

	/** The file permissions for the files stored in the base dir */
	public int $filePermissions;

	/** Stores the properties of an individual album */
	public $entity = false;

	/** Form that can be used to validate and display an album's properties */
	public AlbumForm $form;

	/** Name of the database table storing album properties */
	public string $albumsTable;

	/** Name of the database table storing thumbnail properties */
	public string $thumbnailsTable;

	/** Name of the database table storing picture properties */
	public string $picturesTable;

	/**
	 * Constructs a new album instance.
	 *
	 * @param $dbh Database connection handler
	 * @param $baseURL Base URL of the gallery
	 * @param $pictureDisplayURL URL to the page that displays an individual picture
	 * @param $addMultiplePicturesURL URL to the page allowing one to upload multiple pictures
	 * @param $iconsPath URL to the base directory of the icons of the album
	 * @param $baseDir Base directory where all album artifacts are stored
	 * @param $thumbnailWidth Maximum width of a thumbail image
	 * @param $thumbnailHeight Maximum height of a thumbnail image
	 * @param $pictureWidth Maximum width of a picture
	 * @param $pictureHeight Maximum height of a picture
	 * @param $albumLabels Message labels for translation of the album properties
	 * @param $pictureLabels Message labels for translation of the picture properties
	 * @param $editorSettings Configuration settings for the embedded editor
	 * @param $displayAnchors Whether to display anchors to support redirects to albums and pictures
	 * @param $dirPermissions The file permissions for the directories stored in the base dir
	 * @param $filePermissions The file permissions for the files stored in the base dir
	 * @param $albumsTable Name of the database table storing album properties
	 * @param $thumbnailsTable Name of the database table storing thumbnail properties
	 * @param $picturesTable Name of the database table storing picture properties
	 */
	public function __construct(PDO $dbh, string $baseURL, string $pictureDisplayURL, string $addMultiplePicturesURL, string $iconsPath, string $baseDir, int $thumbnailWidth, int $thumbnailHeight, int $pictureWidth, int $pictureHeight, array $albumLabels = null, array $pictureLabels = null, array $editorSettings = null, bool $displayAnchors = true, int $dirPermissions = 0777, int $filePermissions = 0666, string $albumsTable = "albums", string $thumbnailsTable = "thumbnails", string $picturesTable = "pictures")
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
		$this->displayAnchors = $displayAnchors;
		$this->dirPermissions = $dirPermissions;
		$this->filePermissions = $filePermissions;
		$this->albumsTable = $albumsTable;
		$this->thumbnailsTable = $thumbnailsTable;
		$this->picturesTable = $picturesTable;
	}

	/**
	 * Fetches a requested album from the database
	 *
	 * @param $albumId ID of the album
	 */
	private function fetchEntity($albumId): void
	{
		$stmt = AlbumEntity::queryOne($this->dbh, $albumId, $this->albumsTable);
		$this->entity = $stmt->fetch();
	}

	/**
	 * Constructs a form that can be used to validate and display an album's
	 * properties.
	 *
	 * @param $updateMode Whether the form should be used for updating an existing album
	 */
	private function constructForm($updateMode): void
	{
		$this->form = new AlbumForm($updateMode, $this->albumLabels, $this->editorSettings);
	}

	/**
	 * Constructs a picture with identical configuration settings as this
	 * album.
	 *
	 * @param $albumId ID of the album
	 * @return A picture with the same configuration properties
	 */
	public function constructPicture($albumId): Picture
	{
		return new Picture($this->dbh, $this->baseURL."/".$albumId, $this->baseDir."/".$albumId, $this->pictureDisplayURL, $this->iconsPath, $this->thumbnailWidth, $this->thumbnailHeight, $this->pictureWidth, $this->pictureHeight, $this->pictureLabels, $this->editorSettings, $this->filePermissions, $this->picturesTable, $this->thumbnailsTable, $this->albumsTable);
	}

	/**
	 * Queries all pictures belonging to this album.
	 *
	 * @return A PDO statement that can be used to retrieve picture properties
	 */
	public function queryPictures(): PDOStatement
	{
		return PictureEntity::queryAll($this->dbh, $this->entity["ALBUM_ID"], $this->picturesTable);
	}

	/**
	 * Modifies the state to support the creation of a new album.
	 */
	public function create(): void
	{
		$this->constructForm(false);
		$this->form->fields["__operation"]->value = "insert_album";
	}

	/**
	 * Modifies the state to view a particular album.
	 *
	 * @param $albumId ID of the album
	 */
	public function view(string $albumId): void
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
	 * @param $album Array with properties of an album.
	 * @return true if the album was successfully inserted, else false
	 */
	public function insert(array $album): bool
	{
		$this->constructForm(false);
		$this->form->importValues($album);
		$this->form->checkFields();
		
		if($this->form->checkValid())
		{
			$this->entity = $this->form->exportValues();
			AlbumEntity::insert($this->dbh, $this->entity, $this->albumsTable, $this->thumbnailsTable);
			AlbumFileSet::createAlbumDirectories($this->baseDir, $this->entity["ALBUM_ID"], $this->dirPermissions);
			return true;
		}
		else
			return false;
	}

	/**
	 * Updates an existing album in the database and the filesystem.
	 *
	 * @param $album Array with properties of an album.
	 * @return true if the album was successfully updated, else false
	 */
	public function update(array $album): bool
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
	 * @param $albumId ID of the album
	 */
	public function remove(string $albumId): void
	{
		AlbumEntity::remove($this->dbh, $albumId, $this->albumsTable);
		AlbumFileSet::removeAlbumDirectories($this->baseDir, $albumId);
	}

	/**
	 * Bulk inserts multiple pictures into the database.
	 *
	 * @param $albumId ID of the album
	 * @param $key Key of the field that uploads the file
	 */
	public function insertMultiplePictures(string $albumId, string $key): void
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
	 * @param $albumId ID of the album
	 */
	public function moveLeft(string $albumId): void
	{
		AlbumEntity::moveLeft($this->dbh, $albumId, $this->albumsTable);
	}

	/**
	 * Moves the album right in the overview of albums
	 *
	 * @param $albumId ID of the album
	 */
	public function moveRight(string $albumId): void
	{
		AlbumEntity::moveRight($this->dbh, $albumId, $this->albumsTable);
	}
}
?>
