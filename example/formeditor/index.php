<?php
error_reporting(E_STRICT | E_ALL);

set_include_path("./lib/sbdata:./lib/sbeditor:./lib/sbgallery");

require_once("data/model/Form.class.php");
require_once("data/model/field/TextField.class.php");
require_once("gallery/model/field/HTMLEditorWithGalleryField.class.php");
require_once("data/view/html/form.inc.php");
require_once("gallery/view/html/htmleditorwithgalleryfield.inc.php");

$form = new Form(array(
	"title" => new TextField("Title", true),
	"contents" => new HTMLEditorWithGalleryField("editor1", "Contents", "picturepicker.php", "iframepage.html", "lib/sbeditor/editor/image", true)
));

if(count($_REQUEST) > 0)
{
	$form->importValues($_REQUEST);
	$form->checkFields();
	$valid = $form->checkValid();
}
?>
<!DOCTYPE html>
<html>
	<head>
		<title>HTML editor with gallery integration test</title>
		<link rel="stylesheet" type="text/css" href="style.css">
		<script type="text/javascript" src="lib/sbeditor/editor/scripts/htmleditor.js"></script>
	</head>
	
	<body>
		<?php
		if(count($_REQUEST) > 0 && $valid)
		{
			?>
			<p>You have submitted:</p>
			<h1><?php print($form->fields["title"]->value); ?></h1>
			<div><?php print($form->fields["contents"]->value); ?></div>
			<?php
		}

		displayEditableForm($form, "Submit", "One or more of the field values are incorrect!", "This field is incorrect!");
		?>

		<script type="text/javascript">
		sbeditor.initEditors();
		</script>
	</body>
</html>
