<?php
namespace SBGallery\View\HTML;

function displayHTMLEditorWithGallery($id, $name, $galleryIframePage, $editorIframePage, $iconsPath, $contents = NULL, $width = 60, $galleryHeight = 10, $editorHeight = 20)
{
	?>
	<iframe src="<?php print($galleryIframePage); ?>" style="width: <?php print($width); ?>em; height: <?php print($galleryHeight); ?>em;"></iframe>
	<?php
	\SBEditor\View\HTML\displayHTMLEditor($id, $name, $editorIframePage, $iconsPath, $contents, $width, $editorHeight);
}
?>
