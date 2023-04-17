<?php
namespace SBGallery\Model\Settings\Labels;

/**
 * Collects all labels that a picture displays
 */
class PictureLabels
{
	public string $pictureId;

	public string $title;

	public string $description;

	public string $image;

	public string $submit;

	public string $previous;

	public string $next;

	public string $clearPicture;

	public string $validationErrorMessage;

	public string $fieldErrorMessage;

	public function __construct(string $pictureId = "Id",
		string $title = "Title",
		string $description = "Description",
		string $image = "Image",
		string $submit = "Submit",
		string $previous = "Previous",
		string $next = "Next",
		string $clearPicture = "Clear picture",
		string $validationErrorMessage = "One or more fields are invalid and marked with a red color",
		string $fieldErrorMessage = "This value is incorrect!")
	{
		$this->pictureId = $pictureId;
		$this->title = $title;
		$this->description = $description;
		$this->image = $image;
		$this->submit = $submit;
		$this->previous = $previous;
		$this->next = $next;
		$this->clearPicture = $clearPicture;
		$this->validationErrorMessage = $validationErrorMessage;
		$this->fieldErrorMessage = $fieldErrorMessage;
	}
}
?>
