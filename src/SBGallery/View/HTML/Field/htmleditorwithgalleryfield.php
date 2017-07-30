<?php
namespace SBGallery\View\HTML\Field;
use SBGallery\Model\Field\HTMLEditorWithGalleryField;

function displayHTMLEditorWithGalleryField(HTMLEditorWithGalleryField $field)
{
	print($field->value);
}

function displayEditableHTMLEditorWithGalleryField($name, HTMLEditorWithGalleryField $field)
{
	\SBGallery\View\HTML\displayHTMLEditorWithGallery($field->id, $name, $field->galleryIframePage, $field->editorIframePage, $field->iconsPath, $field->value, $field->width, $field->galleryHeight, $field->editorHeight);
}
?>
