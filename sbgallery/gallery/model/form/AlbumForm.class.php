<?php
require_once("data/model/Form.class.php");
require_once("data/model/field/HiddenField.class.php");
require_once("data/model/field/TextField.class.php");
require_once("data/model/field/TextAreaField.class.php");
require_once("data/model/field/CheckBoxField.class.php");
require_once("editor/model/HTMLEditorField.class.php");

class AlbumForm extends Form
{
	private static $editorSettings = array(
		"id" => "editor1",
		"iframePage" => "iframepage.html",
		"iconsPath" => "lib/sbeditor/editor/image",
		"width" => 60,
		"height" => 20
	);

	public function __construct($updateMode, array $labels, array $editorSettings = null)
	{
		if($editorSettings === null)
			$editorSettings = AlbumForm::$editorSettings;

		$args = array(
			"__operation" => new HiddenField(true),
			"ALBUM_ID" => new TextField($labels["ALBUM_ID"], true, 20, 255),
			"Title" => new TextField($labels["Title"], true, 20, 255),
			"Visible" => new CheckBoxField("Visible"),
			"Description" => new HTMLEditorField($editorSettings["id"], $labels["Description"], $editorSettings["iframePage"], $editorSettings["iconsPath"], false, $editorSettings["width"], $editorSettings["height"]),
		);

		if($updateMode)
			$args["old_ALBUM_ID"] = new HiddenField(true);
		
		parent::__construct($args);
	}
}
?>
