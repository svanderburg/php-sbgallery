<?php
/**
 * @file
 * @brief View-HTML-HTMLEditorWithGallery module
 * @defgroup View-HTML-HTMLEditorWithGallery
 * @{
 */

namespace SBGallery\View\HTML;

/**
 * Displays an iframe with an HTML editor and embedded gallery from which
 * pictures can be selected to embed inside the HTML code.
 *
 * @param $id ID of the HTML editor
 * @param $name Name of the database column
 * @param $galleryIframePage HTML page to display in the gallery iframe
 * @param $editorIframePage HTML page to display in the editor iframe
 * @param $iconsPath Path where to look for the editor's icons
 * @param $contents HTML code to edit (defaults to NULL)
 * @param $width Width of the gallery and rich text editor in characters (defaults to 60)
 * @param $galleryHeight Height of the gallery in characters (defaults to 10)
 * @param $editorHeight Height of the rich text editor in characters (defaults to 20)
 */
function displayHTMLEditorWithGallery(string $id, string $name, string $galleryIframePage, string $editorIframePage, string $iconsPath, string $contents = NULL, int $width = 60, int $galleryHeight = 10, int $editorHeight = 20): void
{
	?>
	<iframe src="<?php print($galleryIframePage); ?>" style="width: <?php print($width); ?>em; height: <?php print($galleryHeight); ?>em;"></iframe>
	<?php
	\SBEditor\View\HTML\displayHTMLEditor($id, $name, $editorIframePage, $iconsPath, $contents, $width, $editorHeight);
}

/**
 * @}
 */
?>
