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
 * @param $editorLabelsParameter Expression referring to the labels parameter that can be used to provide a JavaScript object that defines the labels of the editor (null omits the parameter so that the default labels are used)
 */
function displayHTMLEditorWithGallery(string $id, string $name, string $galleryIframePage, string $editorIframePage, string $iconsPath, string $contents = NULL, int $width = 60, int $galleryHeight = 10, int $editorHeight = 20, string $editorLabelsParameter = null): void
{
	\SBEditor\View\HTML\displayEditorTextAreaDiv($id, $name, $contents, $width, $editorHeight);
	?>
	<script type="text/javascript">
	var editorWithGallery_<?= $id ?> = new sbeditorWithGallery.SBEditorWithGallery('<?= $iconsPath ?>', '<?= $galleryIframePage ?>', '<?= $editorIframePage ?>', <?= $width ?>, <?= $galleryHeight ?>, <?= $editorHeight ?><?= $editorLabelsParameter === null ? "" : (", ".$editorLabelsParameter) ?>);
	editorWithGallery_<?= $id ?>.addEditorWithGalleryCapabilities('<?= $id ?>');
	</script>
	<?php
}

/**
 * @}
 */
?>
