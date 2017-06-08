<?php
require_once("data/view/html/field/textfield.inc.php");
require_once("htmleditorwithgallery.inc.php");

function displayHTMLEditorWithGalleryField(HTMLEditorField $field)
{
	displayTextField($field);
}

function displayEditableHTMLEditorWithGalleryField($name, HTMLEditorWithGalleryField $field)
{
	displayHTMLEditorWithGallery($field->id, $name, $field->galleryIframePage, $field->editorIframePage, $field->iconsPath, $field->value, $field->width, $field->galleryHeight, $field->editorHeight);
}
?>
