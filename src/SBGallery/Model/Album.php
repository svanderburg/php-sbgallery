<?php
namespace SBGallery\Model;
use Exception;
use PDO;
use SBData\Model\Field\CheckBoxField;
use SBData\Model\Field\AcceptableFileNameField;
use SBData\Model\Field\TextField;
use SBCrud\Model\CRUDForm;
use SBEditor\Model\Field\HTMLEditorField;
use SBGallery\Model\Entity\AlbumEntity;
use SBGallery\Model\Entity\PictureEntity;
use SBGallery\Model\Exception\PictureNotFoundException;
use SBGallery\Model\FileSet\PictureFileSet;
use SBGallery\Model\Iterator\PictureThumbnailIterator;
use SBGallery\Model\Settings\AlbumSettings;

/**
 * Representation of a configurable album whose state can be modified.
 */
class Album
{
	/** Database connection to the gallery database */
	public PDO $dbh;

	/** Object that contains album settings */
	public AlbumSettings $settings;

	/** The ID of the album or null if it was not yet inserted into the database */
	public ?string $albumId;

	public CRUDForm $form;

	/**
	 * Constructs a new album instance.
	 *
	 * @param $dbh Database connection to the gallery database
	 * @param $settings Object that contains album settings
	 * @param $albumId The ID of the album
	 */
	public function __construct(PDO $dbh, AlbumSettings $settings, string $albumId = null)
	{
		$this->dbh = $dbh;
		$this->settings = $settings;
		$this->albumId = $albumId;

		$this->form = new CRUDForm(array(
			"ALBUM_ID" => new AcceptableFileNameField($settings->albumLabels->albumId, true, 20, 255),
			"Title" => new TextField($settings->albumLabels->title, true, 20, 255),
			"Visible" => new CheckBoxField($settings->albumLabels->visible),
			"Description" => new HTMLEditorField($settings->albumEditorSettings->id, $settings->albumLabels->description, $settings->albumEditorSettings->iframePage, $settings->albumEditorSettings->iconsPath, false, $settings->albumEditorSettings->width, $settings->albumEditorSettings->height)
		), $settings->operationParam, $settings->urlGenerator->generateAlbumFormURL($albumId), $settings->albumLabels->submit, $settings->albumLabels->validationErrorMessage, $settings->albumLabels->fieldErrorMessage);

		if($albumId === null)
			$this->form->setOperation("insert_album");
		else
			$this->form->setOperation("update_album");
	}

	public function importValues(array $values): void
	{
		$this->form->importValues($values);
	}

	public function exportValues(): array
	{
		return $this->form->exportValues();
	}

	public function checkFields(): void
	{
		$this->form->checkFields();
	}

	public function checkValid(): bool
	{
		return $this->form->checkValid();
	}

	public function deriveAlbumDir(): string
	{
		return $this->settings->baseDir."/".$this->form->fields["ALBUM_ID"]->exportValue();
	}

	/**
	 * Creates a new empty picture that inherits the settings of the album.
	 *
	 * @param $pictureId ID of the picture
	 */
	public function newPicture(string $pictureId = null): Picture
	{
		return new Picture($this->dbh, $this->settings->constructPictureSettings($this->albumId), $this->albumId, $pictureId);
	}

	/**
	 * Queries a picture from the album.
	 *
	 * @param $pictureId ID of the picture
	 * @return The requested picture
	 * @throws PictureNotFoundException If the picture does not exist
	 */
	public function queryPicture(string $pictureId): Picture
	{
		$stmt = PictureEntity::queryOne($this->dbh, $pictureId, $this->albumId, $this->settings->picturesTable);

		if(($row = $stmt->fetch()) === false)
			throw new PictureNotFoundException($this->settings->albumLabels->cannotFindPicture.$pictureId);
		else
		{
			$picture = $this->newPicture($pictureId);
			$picture->importValues($row);
			return $picture;
		}
	}

	/**
	 * Inserts a picture into the database and transfers a user uploaded
	 * picture to the album directory.
	 *
	 * @param $picture Picture to insert
	 */
	public function insertPicture(Picture $picture): void
	{
		$picture->fileType = PictureFileSet::determineImageFileType("Image");

		/* Compose picture row */
		$row = $picture->exportValues();

		PictureEntity::insert($this->dbh, $row, $this->settings->picturesTable); /* Insert picture record into the database */

		/* Move or replace the image file if one has been provided */
		PictureFileSet::generatePictures($_FILES["Image"]["tmp_name"],
			$this->deriveAlbumDir(),
			$picture->form->fields["PICTURE_ID"]->exportValue(),
			$picture->fileType,
			$this->settings->thumbnailWidth,
			$this->settings->thumbnailHeight,
			$this->settings->pictureWidth,
			$this->settings->pictureHeight,
			$this->settings->filePermissions);
	}

	/**
	 * Updates a picture in the database and transfers a user uploaded
	 * picture to the album directory.
	 *
	 * @param $pictureId ID of the picture to update
	 * @param $picture Picture object containing new properties
	 */
	public function updatePicture(string $pictureId, Picture $picture): void
	{
		$oldPicture = $this->queryPicture($pictureId);

		$picture->fileType = PictureFileSet::determineImageFileType("Image");

		/* Compose picture entity object */
		$row = $picture->exportValues();

		PictureEntity::update($this->dbh, $row, $pictureId, $this->albumId, $this->settings->picturesTable); /* Update picture record in the database */

		if($picture->fileType !== null && $oldPicture->fileType !== null && $oldPicture->fileType !== $picture->fileType) // If the filetype has changed, then delete the old pictures
			PictureFileSet::deletePictures($this->deriveAlbumDir(), $pictureId, $oldPicture->fileType);
		else if($pictureId !== $picture->form->fields["PICTURE_ID"]->exportValue()) // If the id has changed, we should change the filenames of the pictures as well
			PictureFileSet::renamePictures($this->deriveAlbumDir(), $pictureId, $picture->form->fields["PICTURE_ID"]->exportValue(), $oldPicture->fileType, $picture->fileType);

		/* Move or replace the image file if one has been provided */
		PictureFileSet::generatePictures($_FILES["Image"]["tmp_name"],
			$this->deriveAlbumDir(),
			$picture->form->fields["PICTURE_ID"]->exportValue(),
			$picture->fileType,
			$this->settings->thumbnailWidth,
			$this->settings->thumbnailHeight,
			$this->settings->pictureWidth,
			$this->settings->pictureHeight,
			$this->settings->filePermissions);
	}

	/**
	 * Removes a picture from the database and the corresponding image files.
	 *
	 * @param $pictureId ID of the picture to remove
	 */
	public function removePicture(string $pictureId): void
	{
		$picture = $this->queryPicture($pictureId);
		PictureEntity::remove($this->dbh, $pictureId, $this->albumId, $this->settings->picturesTable);
		PictureFileSet::deletePictures($this->deriveAlbumDir(), $pictureId, $picture->fileType);
	}

	/**
	 * Moves a picture left in the album
	 *
	 * @param $pictureId ID of the picture to move
	 * @return true if the picture was moved, else false
	 */
	public function moveLeftPicture(string $pictureId): bool
	{
		return PictureEntity::moveLeft($this->dbh, $pictureId, $this->albumId, $this->settings->picturesTable);
	}

	/**
	 * Moves a picture right in the album
	 *
	 * @param $pictureId ID of the picture to move
	 * @return true if the picture was moved, else false
	 */
	public function moveRightPicture(string $pictureId): bool
	{
		return PictureEntity::moveRight($this->dbh, $pictureId, $this->albumId, $this->settings->picturesTable);
	}

	/**
	 * Sets the requested picture as the thumbnail for the album.
	 *
	 * @param $pictureId ID of the picture that should become a thumbnail
	 */
	public function setAsThumbnail(string $pictureId): void
	{
		AlbumEntity::setThumbnail($this->dbh, $pictureId, $this->albumId, $this->settings->thumbnailsTable);
	}

	/**
	 * Clears the image from the requested picture.
	 *
	 * @param $pictureId ID of the picture where to clear the image from
	 */
	public function clearPicture(string $pictureId): void
	{
		$picture = $this->queryPicture($pictureId);
		PictureFileSet::deletePictures($this->deriveAlbumDir(), $pictureId, $picture->fileType);
		PictureEntity::resetFileType($this->dbh, $pictureId, $this->albumId, $this->settings->picturesTable);
	}

	/**
	 * Inserts multiple pictures into the album. It automatically scales
	 * them and adopts their filenames as picture titles.
	 *
	 * @param $key Name of the form field that uploads multiple files
	 */
	public function insertMultiplePictures(string $key): void
	{
		foreach($_FILES[$key]["name"] as $i => $name)
		{
			/* Compose picture entity object */
			$title = pathinfo($name, PATHINFO_FILENAME);

			$row = array(
				"PICTURE_ID" => $title,
				"Title" => $title,
				"Description" => "",
				"FileType" => PictureFileSet::determineImageType($_FILES[$key]["type"][$i]),
				"ALBUM_ID" => $this->albumId
			);

			if($_FILES[$key]["error"][$i] === UPLOAD_ERR_OK)
			{
				PictureEntity::insert($this->dbh, $row, $this->settings->picturesTable); /* Insert picture record into the database */
				PictureFileSet::generatePictures($_FILES[$key]["tmp_name"][$i],
					$this->deriveAlbumDir(),
					$row["PICTURE_ID"],
					$row["FileType"],
					$this->settings->thumbnailWidth,
					$this->settings->thumbnailHeight,
					$this->settings->pictureWidth,
					$this->settings->pictureHeight,
					$this->settings->filePermissions);
			}
			else
				throw new Exception($this->settings->albumLabels->invalidFile.$name);
		}
	}

	/**
	 * Provides an iterator that steps over each thumbnail of a picture in
	 * the album.
	 *
	 * @return Album item iterator that iterates over all available album items
	 */
	public function pictureThumbnailIterator(): PictureThumbnailIterator
	{
		return new PictureThumbnailIterator($this->dbh, $this->albumId, $this->settings);
	}

	/**
	 * Constructs a pictures uploader object that can be used to display
	 * a form that uploads multiple pictures in one operation.
	 *
	 * @return Pictures uploader
	 */
	public function constructPicturesUploader(): PicturesUploader
	{
		return new PicturesUploader($this->albumId, $this->settings);
	}
}
?>
