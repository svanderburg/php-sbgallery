<?php
namespace SBGallery\Model\Field;
use SBData\Model\Field\TextField;

/**
 * Represents the structure of an editable editor that can be used to edit HTML.
 */
class HTMLEditorWithGalleryField extends TextField
{
	/** A unique identifier for the editor div */
	public string $id;

	/** Path to an HTML page that is displayed in the gallery iframe */
	public string $galleryIframePage;

	/** Path to an HTML page that is displayed in the editor iframe */
	public string $editorIframePage;

	/** Path where the editor's icons can be found */
	public string $iconsPath;

	/** Width of the field in em */
	public int $width;

	/** Height of the gallery in em */
	public int $galleryHeight;

	/** Height of the editor in em */
	public int $editorHeight;

	/** Package name */
	public string $package;

	/**
	 * Constructs a new HTMLEditor instance.
	 *
	 * @param $id A unique identifier for the editor div
	 * @param $title Title of the field
	 * @param $galleryIframePage Path to an HTML page that is displayed in the gallery iframe
	 * @param $editorIframePage Path to an HTML page that is displayed in the editor iframe
	 * @param $iconsPath Path where the editor's icons can be found
	 * @param $mandatory Indicates whether a given value is mandatory
	 * @param $width Width of the field in em
	 * @param $galleryHeight Height of the gallery in em
	 * @param $editorHeight Height of the editor in em
	 */
	public function __construct(string $id, string $title, string $galleryIframePage, string $editorIframePage, string $iconsPath, bool $mandatory = false, int $width = 60, int $galleryHeight = 10, int $editorHeight = 20)
	{
		parent::__construct($title, $mandatory);
		$this->id = $id;
		$this->galleryIframePage = $galleryIframePage;
		$this->editorIframePage = $editorIframePage;
		$this->iconsPath = $iconsPath;
		$this->width = $width;
		$this->galleryHeight = $galleryHeight;
		$this->editorHeight = $editorHeight;
		$this->package = "SBGallery";
	}
}
?>
