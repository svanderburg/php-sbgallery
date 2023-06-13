<?php
namespace SBGallery\Model\Settings\Labels;

/**
 * Collects all labels that an album displays.
 */
class AlbumLabels
{
	public string $albumId;

	public string $title;

	public string $visible;

	public string $description;

	public string $addPicture;

	public string $addMultiplePictures;

	public string $moveLeft;

	public string $moveRight;

	public string $setAsThumbnail;

	public string $remove;

	public string $submit;

	public string $sendFiles;

	public string $previous;

	public string $next;

	public string $invalidFile;

	public string $validationErrorMessage;

	public string $fieldErrorMessage;

	public string $cannotFindPicture;

	public function __construct(string $albumId = "Id",
		string $title = "Title",
		string $visible = "Visible",
		string $description = "Description",
		string $addPicture = "Add picture",
		string $addMultiplePictures = "Add multiple pictures",
		string $moveLeft = "Move left",
		string $moveRight = "Move right",
		string $setAsThumbnail = "Set as thumbnail",
		string $remove = "Remove",
		string $submit = "Submit",
		string $sendFiles = "Send files",
		string $previous = "&laquo; Previous",
		string $next = "Next &raquo;",
		string $invalidFile = "Invalid file: ",
		string $validationErrorMessage = "One or more fields are invalid and marked with a red color",
		string $fieldErrorMessage = "This value is incorrect!",
		string $cannotFindPicture = "Cannot find picture with ID: ")
	{
		$this->albumId = $albumId;
		$this->title = $title;
		$this->visible = $visible;
		$this->description = $description;
		$this->addPicture = $addPicture;
		$this->addMultiplePictures = $addMultiplePictures;
		$this->moveLeft = $moveLeft;
		$this->moveRight = $moveRight;
		$this->setAsThumbnail = $setAsThumbnail;
		$this->remove = $remove;
		$this->submit = $submit;
		$this->previous = $previous;
		$this->next = $next;
		$this->sendFiles = $sendFiles;
		$this->invalidFile =  $invalidFile;
		$this->validationErrorMessage = $validationErrorMessage;
		$this->fieldErrorMessage = $fieldErrorMessage;
		$this->cannotFindPicture = $cannotFindPicture;
	}
}
?>
