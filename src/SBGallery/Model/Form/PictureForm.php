<?php
namespace SBGallery\Model\Form;
use SBData\Model\Form;
use SBData\Model\Field\FileField;
use SBData\Model\Field\HiddenField;
use SBData\Model\Field\TextField;
use SBEditor\Model\Field\HTMLEditorField;

class PictureForm extends Form
{
	private static $editorSettings = array(
		"id" => "editor1",
		"iframePage" => "iframepage.html",
		"iconsPath" => "image/editor",
		"width" => 60,
		"height" => 20
	);

	public function __construct($updateMode, array $labels, array $editorSettings = null)
	{
		if($editorSettings === null)
			$editorSettings = PictureForm::$editorSettings;

		$args = array(
			"__operation" => new HiddenField(true),
			"ALBUM_ID" => new HiddenField(true),
			"PICTURE_ID" => new TextField($labels["PICTURE_ID"], true, 20, 255),
			"Title" => new TextField($labels["Title"], true, 20, 255),
			"Description" => new HTMLEditorField($editorSettings["id"], $labels["Description"], $editorSettings["iframePage"], $editorSettings["iconsPath"], false, $editorSettings["width"], $editorSettings["height"]),
			"Image" => new FileField($labels["Image"], array("image/gif", "image/jpeg", "image/png"), false)
		);
		
		if($updateMode)
			$args["old_PICTURE_ID"] = new HiddenField(true);
		
		parent::__construct($args);
	}
}
