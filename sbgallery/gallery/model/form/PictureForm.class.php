<?php
require_once("data/model/Form.class.php");
require_once("data/model/field/TextField.class.php");
require_once("data/model/field/TextAreaField.class.php");
require_once("data/model/field/HiddenField.class.php");
require_once("data/model/field/FileField.class.php");
require_once("editor/model/HTMLEditorField.class.php");

class PictureForm extends Form
{
	private static $editorSettings = array(
		"id" => "editor1",
		"iframePage" => "iframepage.html",
		"iconsPath" => "lib/sbeditor/editor/image"
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
			"Description" => new HTMLEditorField($editorSettings["id"], $labels["Description"], $editorSettings["iframePage"], $editorSettings["iconsPath"], false),
			"Image" => new FileField($labels["Image"], array("image/gif", "image/jpeg", "image/png"), false)
		);
		
		if($updateMode)
			$args["old_PICTURE_ID"] = new HiddenField(true);
		
		parent::__construct($args);
	}
}
