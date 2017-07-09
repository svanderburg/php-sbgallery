<?php
require_once("htmleditorwithgallery.inc.php");

function displayHTMLEditorWithGalleryField(HTMLEditorWithGalleryField $field)
{
	print($field->value);
}

function displayEditableHTMLEditorWithGalleryField($name, HTMLEditorWithGalleryField $field)
{
	displayHTMLEditorWithGallery($field->id, $name, $field->galleryIframePage, $field->editorIframePage, $field->iconsPath, $field->value, $field->width, $field->galleryHeight, $field->editorHeight);
}
?>
