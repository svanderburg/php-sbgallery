<?php
namespace SBGallery\Model;
use Exception;
use PDO;
use SBGallery\Model\Form\PictureForm;
use SBGallery\Model\Entity\AlbumEntity;
use SBGallery\Model\Entity\PictureEntity;
use SBGallery\Model\FileSet\PictureFileSet;

/**
 * A representation of a picture whose state can be modified.
 */
class Picture
{
	private static $referenceLabels = array(
		"Gallery" => "Gallery",
		"PICTURE_ID" => "Id",
		"Title" => "Title",
		"Description" => "Description",
		"Image" => "Image",
		"Remove image" => "Remove image",
		"Submit" => "Submit",
		"Form invalid" => "One or more fields are incorrectly specified and marked with a red color!",
		"Field invalid" => "This field is incorrectly specified!"
	);

	/** Database connection handler */
	public $dbh;

	/** Base URL of the album */
	public $baseURL;

	/** Directory in which the album's pictures reside */
	public $albumDir;

	/** URL to the page that displays an individual picture */
	public $pictureDisplayURL;

	/** URL to the base directory of the icons of the album */
	public $iconsPath;

	/** Maximum width of a thumbnail image */
	public $thumbnailWidth;

	/** Maximum height of a thumbnail image */
	public $thumbnailHeight;

	/** Maximum width of a picture */
	public $pictureWidth;

	/** Maximum height of a picture */
	public $pictureHeight;

	/** The file permissions for the files stored in the base dir */
	public $filePermissions;

	/** Message labels for translation of the picture properties */
	public $labels;

	/** Configuration settings for the embedded editor */
	public $editorSettings;

	/** Form that can be used to validate and display a picture's properties */
	public $form;

	/** Stores the properties of an individual picture */
	public $entity = false;

	/** Name of the database table storing picture properties */
	public $picturesTable;

	/** Name of the database table storing thumbnail properties */
	public $thumbnailsTable;

	/** Name of the database table storing album properties */
	public $albumsTable;

	/**
	 * Creates a new picture object.
	 *
	 * @param DBO $dbh Database connection handler
	 * @param string $baseURL Base URL of the album
	 * @param string $albumDir Directory in which the album's pictures reside
	 * @param string $pictureDisplayURL URL to the page that displays an individual picture
	 * @param string $iconsPath URL to the base directory of the icons of the album
	 * @param int $thumbnailWidth Maximum width of a thumbnail image
	 * @param int $thumbnailHeight Maximum height of a thumbnail image
	 * @param int $pictureWidth Maximum width of a picture
	 * @param int $pictureHeight Maximum height of a picture
	 * @param array $labels Message labels for translation of the picture properties
	 * @param array $editorSettings Configuration settings for the embedded editor
	 * @param int $filePermissions The file permissions for the files stored in the base dir
	 * @param string $picturesTable Name of the database table storing picture properties
	 * @param string $thumbnailsTable Name of the database table storing thumbnail properties
	 * @param string $albumsTable Name of the database table storing album properties
	 */
	public function __construct(PDO $dbh, $baseURL, $albumDir, $pictureDisplayURL, $iconsPath, $thumbnailWidth, $thumbnailHeight, $pictureWidth, $pictureHeight, array $labels = null, array $editorSettings = null, $filePermissions = 0666, $picturesTable = "pictures", $thumbnailsTable = "thumbnails", $albumsTable = "albums")
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
	 * @param bool $updateMode Whether the form should be used for updating an existing picture
	 */
	private function constructForm($updateMode)
	{
		$this->form = new PictureForm($updateMode, $this->labels, $this->editorSettings);
	}

	/**
	 * Fetches a requested picture from the database
	 *
	 * @param string $picture ID of the picture
	 * @param string $albumId ID of the album where the picture belongs to
	 */
	private function fetchEntity($pictureId, $albumId)
	{
		$stmt = PictureEntity::queryOne($this->dbh, $pictureId, $albumId, $this->picturesTable);
		$this->entity = $stmt->fetch();
	}

	/**
	 * Modifies the state to support the creation of a new picture.
	 *
	 * @param string $albumId ID of the album where the picture belongs to
	 */
	public function create($albumId)
	{
		$this->constructForm(false);
		$this->form->fields["ALBUM_ID"]->value = $albumId;
		$this->form->fields["__operation"]->value = "insert_picture";
	}

	/**
	 * Modifies the state to view a particular album.
	 *
	 * @param string $pictureId ID of the picture
	 * @param string $albumId ID of the album where the picture belongs to
	 */
	public function view($pictureId, $albumId)
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
	 * @param array $picture Array with properties of a picture
	 */
	public function insert(array $picture)
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
	 * @param array $picture Array with properties of a picture.
	 */
	public function update(array $picture)
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
	 * @param string $pictureId ID of the picture
	 * @param string $albumId ID of the album where the picture belongs to
	 */
	public function remove($pictureId, $albumId)
	{
		$this->fetchEntity($pictureId, $albumId); // Fetch picture entity object to determine the file type
		PictureEntity::remove($this->dbh, $this->entity["PICTURE_ID"], $this->entity["ALBUM_ID"], $this->picturesTable);
		PictureFileSet::deletePictures($this->albumDir, $this->entity["PICTURE_ID"], $this->entity["FileType"]);
	}

	/**
	 * Sets a picture as default thumbnail image for the album.
	 *
	 * @param string $pictureId ID of the picture
	 * @param string $albumId ID of the album where the picture belongs to
	 */
	public function setAsThumbnail($pictureId, $albumId)
	{
		AlbumEntity::setThumbnail($this->dbh, $pictureId, $albumId, $this->thumbnailsTable);
	}

	/**
	 * Moves the picture left in the overview of pictures
	 *
	 * @param string $pictureId ID of the picture
	 * @param string $albumId ID of the album where the picture belongs to
	 */
	public function moveLeft($pictureId, $albumId)
	{
		PictureEntity::moveLeft($this->dbh, $pictureId, $albumId, $this->picturesTable);
	}

	/**
	 * Moves the picture right in the overview of pictures
	 *
	 * @param string $pictureId ID of the picture
	 * @param string $albumId ID of the album where the picture belongs to
	 */
	public function moveRight($pictureId, $albumId)
	{
		PictureEntity::moveRight($this->dbh, $pictureId, $albumId, $this->picturesTable);
	}

	/**
	 * Removes the displayed image files
	 *
	 * @param string $pictureId ID of the picture
	 * @param string $albumId ID of the album where the picture belongs to
	 */
	public function removePictureImage($pictureId, $albumId)
	{
		$this->fetchEntity($pictureId, $albumId); // Fetch picture entity object to determine the file type
		PictureFileSet::deletePictures($this->albumDir, $this->entity["PICTURE_ID"], $this->entity["FileType"]);
		PictureEntity::resetFileType($this->dbh, $this->entity["PICTURE_ID"], $this->entity["ALBUM_ID"], $this->picturesTable);
	}

	/**
	 * Queries the picture that comes before the current picture in the album
	 *
	 * @return PDOStatement A PDO statement that can be used to retrieve the result
	 */
	public function queryPredecessor()
	{
		return PictureEntity::queryPredecessor($this->dbh, $this->entity["ALBUM_ID"], $this->entity["Ordering"], $this->picturesTable);
	}

	/**
	 * Queries the picture that comes after the current picture in the album
	 *
	 * @return PDOStatement A PDO statement that can be used to retrieve the result
	 */
	public function querySuccessor()
	{
		return PictureEntity::querySuccessor($this->dbh, $this->entity["ALBUM_ID"], $this->entity["Ordering"], $this->picturesTable);
	}

	/**
	 * Queries the album to which the current picture belongs.
	 *
	 * @return PDOStatement A PDO statement that can be used to retrieve the result
	 */
	public function queryAlbum()
	{
		return AlbumEntity::queryOne($this->dbh, $this->entity["ALBUM_ID"], $this->albumsTable);
	}
}
?>
