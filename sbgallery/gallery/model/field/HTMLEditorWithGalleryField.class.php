<?php
require_once("data/model/field/TextField.class.php");

/**
 * Represents the structure of an editable editor that can be used to edit HTML.
 */
class HTMLEditorWithGalleryField extends TextField
{
	/**
	 * Constructs a new HTMLEditor instance.
	 *
	 * @param string $id A unique identifier for the editor div
	 * @param string $title Title of the field
	 * @param string $galleryIframePage Path to an HTML page that is displayed in the gallery iframe
	 * @param string $editorIframePage Path to an HTML page that is displayed in the editor iframe
	 * @param string $iconsPath Path where the editor's icons can be found
	 * @param bool $mandatory Indicates whether a given value is mandatory
	 * @param int $width Width of the field in em
	 * @param int $galleryHeight Height of the gallery in em
	 * @param int $editorHeight Height of the editor in em
	 */
	public function __construct($id, $title, $galleryIframePage, $editorIframePage, $iconsPath, $mandatory = false, $width = 60, $galleryHeight = 10, $editorHeight = 20)
	{
		parent::__construct($title, $mandatory);
		$this->id = $id;
		$this->galleryIframePage = $galleryIframePage;
		$this->editorIframePage = $editorIframePage;
		$this->iconsPath = $iconsPath;
		$this->width = $width;
		$this->galleryHeight = $galleryHeight;
		$this->editorHeight = $editorHeight;
	}
}
?>
