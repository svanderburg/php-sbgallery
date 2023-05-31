<?php
namespace SBGallery\Model;
use PDO;
use SBData\Model\Label\TextLabel;
use SBData\Model\Field\AcceptableFileNameField;
use SBData\Model\Field\FileField;
use SBData\Model\Field\HiddenField;
use SBData\Model\Field\HiddenAcceptableFileNameField;
use SBData\Model\Field\TextField;
use SBEditor\Model\Field\HTMLEditorField;
use SBCrud\Model\CRUDForm;
use SBGallery\Model\Entity\PictureEntity;
use SBGallery\Model\Settings\PictureSettings;

/**
 * Representation of a configurable picture whose state can be modified.
 */
class Picture
{
	/** Database connection to the gallery database */
	public PDO $dbh;

	/** Object that contains picture settings */
	public PictureSettings $settings;

	/** The ID of the album in which the picture is stored */
	public string $albumId;

	/** The ID of the picture or null if it was not yet inserted into the database */
	public ?string $pictureId;

	public CRUDForm $form;

	/** The file type of the image or null if it was not stored on disk */
	public ?string $fileType;

	/** The ordering index number of the picture */
	public int $ordering;

	/**
	 * Constructs a new picture instance.
	 *
	 * @param $dbh Database connection to the gallery database
	 * @param $settings Object that contains picture settings
	 * @param $albumId The ID of the album in which the picture is stored
	 * @param $pictureId The ID of the picture
	 */
	public function __construct(PDO $dbh, PictureSettings $settings, string $albumId, string $pictureId = null)
	{
		$this->dbh = $dbh;
		$this->settings = $settings;
		$this->albumId = $albumId;
		$this->pictureId = $pictureId;

		$this->form = new CRUDForm(array(
			"ALBUM_ID" => new HiddenAcceptableFileNameField(true, 255),
			"PICTURE_ID" => new AcceptableFileNameField($settings->labels->pictureId, true, 20, 255),
			"Title" => new TextField($settings->labels->title, true, 20, 255),
			"Description" => new HTMLEditorField($settings->editorSettings->id, $settings->labels->description, $settings->editorSettings->iframePage, $settings->editorSettings->iconsPath, false, $settings->editorSettings->width, $settings->editorSettings->height),
			"Image" => new FileField($settings->labels->image, array("image/gif", "image/jpeg", "image/png"), false)
		), $settings->operationParam, $settings->urlGenerator->generatePictureFormURL($albumId, $pictureId), new TextLabel($settings->labels->submit), $settings->labels->validationErrorMessage, $settings->labels->fieldErrorMessage);

		$this->fileType = null;

		$this->form->fields["ALBUM_ID"]->importValue($albumId);

		if($pictureId === null)
			$this->form->setOperation("insert_picture");
		else
			$this->form->setOperation("update_picture");
	}

	public function importValues(array $values): void
	{
		$this->form->importValues($values);

		if(array_key_exists("FileType", $values))
			$this->fileType = $values["FileType"];

		if(array_key_exists("Ordering", $values))
			$this->ordering = $values["Ordering"];
	}

	public function exportValues(): array
	{
		$values = $this->form->exportValues();
		$values["FileType"] = $this->fileType;
		//$values["Ordering"] = $this->ordering;
		return $values;
	}

	public function checkFields(): void
	{
		$this->form->checkFields();
	}

	public function checkValid(): bool
	{
		return $this->form->checkValid();
	}

	/**
	 * Queries the ID of the predecessor picture in the album.
	 *
	 * @return The ID of the predecessor picture or null if there is none
	 */
	public function queryPredecessorId(): ?string
	{
		$stmt = PictureEntity::queryPredecessor($this->dbh, $this->albumId, $this->ordering, $this->settings->picturesTable);
		if(($row = $stmt->fetch()) === false)
			return null;
		else
			return $row["PICTURE_ID"];
	}

	/**
	 * Queries the ID of the successor picture in the album.
	 *
	 * @return The ID of the successor picture or null if there is none
	 */
	public function querySuccessorId(): ?string
	{
		$stmt = PictureEntity::querySuccessor($this->dbh, $this->albumId, $this->ordering, $this->settings->picturesTable);
		if(($row = $stmt->fetch()) === false)
			return null;
		else
			return $row["PICTURE_ID"];
	}
}
?>
