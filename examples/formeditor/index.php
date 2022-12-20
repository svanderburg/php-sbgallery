<?php
error_reporting(E_STRICT | E_ALL);

require_once("../../vendor/autoload.php");

use SBData\Model\Form;
use SBData\Model\Field\TextField;
use SBGallery\Model\Field\HTMLEditorWithGalleryField;

$form = new Form(array(
	"title" => new TextField("Title", true),
	"contents" => new HTMLEditorWithGalleryField("editor1", "Contents", "picturepicker.php", "iframepage.html", "image/editor", true)
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
		<script type="text/javascript" src="scripts/htmleditor.js"></script>
		<script type="text/javascript" src="../../scripts/htmleditorwithgallery.js"></script>
	</head>

	<body>
		<?php
		if(count($_REQUEST) > 0 && $valid)
		{
			?>
			<p>You have submitted:</p>
			<h1><?= $form->fields["title"]->exportValue() ?></h1>
			<div><?= $form->fields["contents"]->exportValue() ?></div>
			<?php
		}

		\SBData\View\HTML\displayEditableForm($form, "Submit", "One or more of the field values are incorrect!", "This field is incorrect!");
		?>

		<script type="text/javascript">
		sbeditor.initEditors();
		</script>
	</body>
</html>
