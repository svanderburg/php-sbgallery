<?php
namespace SBGallery\Model\Form;
use SBData\Model\Field\HiddenField;
use SBData\Model\Field\CheckBoxField;
use SBData\Model\Field\AcceptableFileNameField;
use SBData\Model\Field\TextField;
use SBCrud\Model\CRUDForm;
use SBEditor\Model\Field\HTMLEditorField;

class AlbumForm extends CRUDForm
{
	private static array $editorSettings = array(
		"id" => "editor1",
		"iframePage" => "iframepage.html",
		"iconsPath" => "image/editor",
		"width" => 60,
		"height" => 20
	);

	public function __construct(bool $updateMode, array $labels, array $editorSettings = null)
	{
		if($editorSettings === null)
			$editorSettings = AlbumForm::$editorSettings;

		$args = array(
			"ALBUM_ID" => new AcceptableFileNameField($labels["ALBUM_ID"], true, 20, 255),
			"Title" => new TextField($labels["Title"], true, 20, 255),
			"Visible" => new CheckBoxField($labels["Visible"]),
			"Description" => new HTMLEditorField($editorSettings["id"], $labels["Description"], $editorSettings["iframePage"], $editorSettings["iconsPath"], false, $editorSettings["width"], $editorSettings["height"]),
		);

		if($updateMode)
			$args["old_ALBUM_ID"] = new HiddenField(true);
		
		parent::__construct($args);
	}
}
?>
