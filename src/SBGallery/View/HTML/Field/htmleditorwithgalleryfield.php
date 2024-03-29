<?php
/**
 * @file
 * @brief View-HTML-Field-HTMLEditorWithGalleryField module
 * @defgroup View-HTML-Field-HTMLEditorWithGalleryField
 * @{
 */
namespace SBGallery\View\HTML\Field;
use SBGallery\Model\Field\HTMLEditorWithGalleryField;

function displayHTMLEditorWithGalleryField(HTMLEditorWithGalleryField $field)
{
	print($field->exportValue());
}

function displayEditableHTMLEditorWithGalleryField(string $name, HTMLEditorWithGalleryField $field)
{
	\SBGallery\View\HTML\displayHTMLEditorWithGallery($field->id, $name, $field->galleryIframePage, $field->editorIframePage, $field->iconsPath, $field->exportValue(), $field->width, $field->galleryHeight, $field->editorHeight, $field->editorLabelsParameter);
}

/**
 * @}
 */
?>
