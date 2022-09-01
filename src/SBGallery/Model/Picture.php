<?php
namespace SBGallery\Model;
use Exception;
use PDO;
use PDOStatement;
use SBGallery\Model\Form\PictureForm;
use SBGallery\Model\Entity\AlbumEntity;
use SBGallery\Model\Entity\PictureEntity;
use SBGallery\Model\FileSet\PictureFileSet;

/**
 * A representation of a picture whose state can be modified.
 */
class Picture
{
	private static array $referenceLabels = array(
		"Gallery" => "Gallery",
		"PICTURE_ID" => "Id",
		"Title" => "Title",
		"Description" => "Description",
		"Image" => "Image",
		"Remove image" => "Remove image",
		"Submit" => "Submit",
		"Form invalid" => "One or more fields are incorrectly specified and marked with a red color!",
		"Field invalid" => "This field is incorrectly specified!",
		"Previous" => "Previous",
		"Next" => "Next"
	);

	/** Database connection handler */
	public PDO $dbh;

	/** Base URL of the album */
	public string $baseURL;

	/** Directory in which the album's pictures reside */
	public string $albumDir;

	/** URL to the page that displays an individual picture */
	public string $pictureDisplayURL;

	/** URL to the base directory of the icons of the album */
	public string $iconsPath;

	/** Maximum width of a thumbnail image */
	public int $thumbnailWidth;

	/** Maximum height of a thumbnail image */
	public int $thumbnailHeight;

	/** Maximum width of a picture */
	public int $pictureWidth;

	/** Maximum height of a picture */
	public int $pictureHeight;

	/** The file permissions for the files stored in the base dir */
	public int $filePermissions;

	/** Message labels for translation of the picture properties */
	public array $labels;

	/** Configuration settings for the embedded editor */
	public ?array $editorSettings;

	/** Form that can be used to validate and display a picture's properties */
	public PictureForm $form;

	/** Stores the properties of an individual picture */
	public $entity = false;

	/** Name of the database table storing picture properties */
	public string $picturesTable;

	/** Name of the database table storing thumbnail properties */
	public string $thumbnailsTable;

	/** Name of the database table storing album properties */
	public string $albumsTable;

	/**
	 * Creates a new picture object.
	 *
	 * @param $dbh Database connection handler
	 * @param $baseURL Base URL of the album
	 * @param $albumDir Directory in which the album's pictures reside
	 * @param $pictureDisplayURL URL to the page that displays an individual picture
	 * @param $iconsPath URL to the base directory of the icons of the album
	 * @param $thumbnailWidth Maximum width of a thumbnail image
	 * @param $thumbnailHeight Maximum height of a thumbnail image
	 * @param $pictureWidth Maximum width of a picture
	 * @param $pictureHeight Maximum height of a picture
	 * @param $labels Message labels for translation of the picture properties
	 * @param $editorSettings Configuration settings for the embedded editor
	 * @param $filePermissions The file permissions for the files stored in the base dir
	 * @param $picturesTable Name of the database table storing picture properties
	 * @param $thumbnailsTable Name of the database table storing thumbnail properties
	 * @param $albumsTable Name of the database table storing album properties
	 */
	public function __construct(PDO $dbh, string $baseURL, string $albumDir, string $pictureDisplayURL, string $iconsPath, int $thumbnailWidth, int $thumbnailHeight, int $pictureWidth, int $pictureHeight, array $labels = null, array $editorSettings = null, int $filePermissions = 0666, string $picturesTable = "pictures", string $thumbnailsTable = "thumbnails", string $albumsTable = "albums")
	{
		$this->dbh = $dbh;
		$this->baseURL = $baseURL;
		$this->albumDir = $albumDir;
		$this->pictureDisplayURL = $pictureDisplayURL;
		$this->iconsPath = $iconsPath;
		$this->thumbnailWidth = $thumbnailWidth;
		$this->thumbnailHeight = $thumbnailHeight;
		$this->pictureWidth = $pictureWidth;
		$this->pictureHeight = $pictureHeight;
		$this->filePermissions = $filePermissions;
		if($labels === null)
			$this->labels = Picture::$referenceLabels;
		else
			$this->labels = $labels;
		$this->editorSettings = $editorSettings;
		$this->picturesTable = $picturesTable;
		$this->thumbnailsTable = $thumbnailsTable;
		$this->albumsTable = $albumsTable;
	}

	/**
	 * Constructs a form that can be used to validate and display a
	 * picture's properties.
	 *
	 * @param $updateMode Whether the form should be used for updating an existing picture
	 */
	private function constructForm(bool $updateMode): void
	{
		$this->form = new PictureForm($updateMode, $this->labels, $this->editorSettings);
	}

	/**
	 * Fetches a requested picture from the database
	 *
	 * @param $picture ID of the picture
	 * @param $albumId ID of the album where the picture belongs to
	 */
	private function fetchEntity(string $pictureId, string $albumId): void
	{
		$stmt = PictureEntity::queryOne($this->dbh, $pictureId, $albumId, $this->picturesTable);
		$this->entity = $stmt->fetch();
	}

	/**
	 * Modifies the state to support the creation of a new picture.
	 *
	 * @param $albumId ID of the album where the picture belongs to
	 */
	public function create(string $albumId): void
	{
		$this->constructForm(false);
		$this->form->fields["ALBUM_ID"]->value = $albumId;
		$this->form->fields["__operation"]->value = "insert_picture";
	}

	/**
	 * Modifies the state to view a particular album.
	 *
	 * @param $pictureId ID of the picture
	 * @param $albumId ID of the album where the picture belongs to
	 */
	public function view(string $pictureId, string $albumId): void
	{
		$this->constructForm(true);
		$this->fetchEntity($pictureId, $albumId);

		if($this->entity === false)
			throw new Exception(array_key_exists("cannotFindPicture", $this->albumLabels) ? $this->albumLabels["cannotFindPicture"] : "Cannot find requested picture!");
		else
		{
			$this->form->importValues($this->entity);
			$this->form->fields["__operation"]->value = "update_picture";
			$this->form->fields["old_PICTURE_ID"]->value = $this->entity["PICTURE_ID"];
		}
	}

	/**
	 * Inserts a given picture into the database and uploads the files into
	 * the gallery base dir.
	 *
	 * @param $picture Array with properties of a picture
	 * @return true if the picture was successfully inserted, else false
	 */
	public function insert(array $picture): bool
	{
		$this->constructForm(false);
		$this->form->importValues($picture);
		$this->form->checkFields();

		if($this->form->checkValid())
		{
			/* Compose picture entity object */
			$this->entity = $this->form->exportValues();
			$this->entity["FileType"] = PictureFileSet::determineImageFileType("Image");

			PictureEntity::insert($this->dbh, $this->entity, $this->picturesTable); /* Insert picture record into the database */

			/* Move or replace the image file if one has been provided */
			PictureFileSet::generatePictures($_FILES["Image"]["tmp_name"], $this->albumDir, $this->entity["PICTURE_ID"], $this->entity["FileType"], $this->thumbnailWidth, $this->thumbnailHeight, $this->pictureWidth, $this->pictureHeight, $this->filePermissions);
			return true;
		}
		else
			return false;
	}

	/**
	 * Updates an existing picture in the database and the filesystem.
	 *
	 * @param $picture Array with properties of a picture.
	 * @return true if the pictures was updated, else false
	 */
	public function update(array $picture): bool
	{
		$this->constructForm(true);
		$this->form->importValues($picture);
		$this->form->checkFields();

		if($this->form->checkValid())
		{
			$this->fetchEntity($this->form->fields["old_PICTURE_ID"]->value, $this->form->fields["ALBUM_ID"]->value); // Fetch again to find out what fileType it has
			$oldPictureId = $this->form->fields["old_PICTURE_ID"]->value;
			$oldFileType = $this->entity["FileType"];

			/* Compose picture entity object */
			$this->entity = $this->form->exportValues();
			$this->entity["FileType"] = PictureFileSet::determineImageFileType("Image");

			PictureEntity::update($this->dbh, $this->entity, $oldPictureId, $this->entity["ALBUM_ID"], $this->picturesTable); /* Update picture record in the database */

			if($this->entity["FileType"] !== null && $oldFileType !== null && $oldFileType !== $this->entity["FileType"]) // If the filetype has changed, then delete the old pictures
				PictureFileSet::deletePictures($this->albumDir, $this->entity["PICTURE_ID"], $this->entity["FileType"]);
			else if($oldPictureId !== $this->entity["PICTURE_ID"]) // If the id has changed, we should change the filenames of the pictures as well
				PictureFileSet::renamePictures($this->albumDir, $oldPictureId, $this->entity["PICTURE_ID"], $oldFileType, $this->entity["FileType"]);

			/* Move or replace the image file if one has been provided */
			PictureFileSet::generatePictures($_FILES["Image"]["tmp_name"], $this->albumDir, $this->entity["PICTURE_ID"], $this->entity["FileType"], $this->thumbnailWidth, $this->thumbnailHeight, $this->pictureWidth, $this->pictureHeight, $this->filePermissions);
			return true;
		}
		else
			return false;
	}

	/**
	 * Removes a picture from the database and the filesystem.
	 *
	 * @param $pictureId ID of the picture
	 * @param $albumId ID of the album where the picture belongs to
	 */
	public function remove(string $pictureId, string $albumId): void
	{
		$this->fetchEntity($pictureId, $albumId); // Fetch picture entity object to determine the file type
		PictureEntity::remove($this->dbh, $this->entity["PICTURE_ID"], $this->entity["ALBUM_ID"], $this->picturesTable);
		PictureFileSet::deletePictures($this->albumDir, $this->entity["PICTURE_ID"], $this->entity["FileType"]);
	}

	/**
	 * Sets a picture as default thumbnail image for the album.
	 *
	 * @param $pictureId ID of the picture
	 * @param $albumId ID of the album where the picture belongs to
	 */
	public function setAsThumbnail(string $pictureId, string $albumId): void
	{
		AlbumEntity::setThumbnail($this->dbh, $pictureId, $albumId, $this->thumbnailsTable);
	}

	/**
	 * Moves the picture left in the overview of pictures
	 *
	 * @param $pictureId ID of the picture
	 * @param $albumId ID of the album where the picture belongs to
	 */
	public function moveLeft(string $pictureId, string $albumId): void
	{
		PictureEntity::moveLeft($this->dbh, $pictureId, $albumId, $this->picturesTable);
	}

	/**
	 * Moves the picture right in the overview of pictures
	 *
	 * @param $pictureId ID of the picture
	 * @param $albumId ID of the album where the picture belongs to
	 */
	public function moveRight(string $pictureId, string $albumId): void
	{
		PictureEntity::moveRight($this->dbh, $pictureId, $albumId, $this->picturesTable);
	}

	/**
	 * Removes the displayed image files
	 *
	 * @param $pictureId ID of the picture
	 * @param $albumId ID of the album where the picture belongs to
	 */
	public function removePictureImage(string $pictureId, string $albumId): void
	{
		$this->fetchEntity($pictureId, $albumId); // Fetch picture entity object to determine the file type
		PictureFileSet::deletePictures($this->albumDir, $this->entity["PICTURE_ID"], $this->entity["FileType"]);
		PictureEntity::resetFileType($this->dbh, $this->entity["PICTURE_ID"], $this->entity["ALBUM_ID"], $this->picturesTable);
	}

	/**
	 * Queries the picture that comes before the current picture in the album
	 *
	 * @return A PDO statement that can be used to retrieve the result
	 */
	public function queryPredecessor(): PDOStatement
	{
		return PictureEntity::queryPredecessor($this->dbh, $this->entity["ALBUM_ID"], $this->entity["Ordering"], $this->picturesTable);
	}

	/**
	 * Queries the picture that comes after the current picture in the album
	 *
	 * @return A PDO statement that can be used to retrieve the result
	 */
	public function querySuccessor(): PDOStatement
	{
		return PictureEntity::querySuccessor($this->dbh, $this->entity["ALBUM_ID"], $this->entity["Ordering"], $this->picturesTable);
	}

	/**
	 * Queries the album to which the current picture belongs.
	 *
	 * @return A PDO statement that can be used to retrieve the result
	 */
	public function queryAlbum(): PDOStatement
	{
		return AlbumEntity::queryOne($this->dbh, $this->entity["ALBUM_ID"], $this->albumsTable);
	}
}
?>
