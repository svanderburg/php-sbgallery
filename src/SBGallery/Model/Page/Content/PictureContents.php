<?php
namespace SBGallery\Model\Page\Content;
use SBLayout\Model\Page\Page;

class PictureContents extends OverridedContents
{
	public function __construct(string $pictureEditorLabelsFile = null, array $sections = null, string $gallerySection = "contents", string $view = "HTML", ?array $styles = array(), ?array $scripts = array())
	{
		$htmlEditorJsPath = Page::computeBaseURL()."/scripts/htmleditor.js";
		array_push($scripts, $htmlEditorJsPath);

		if($pictureEditorLabelsFile !== null)
			array_push($scripts, $pictureEditorLabelsFile);

		parent::__construct("gallery/album/picture.php", true, "gallery/album/picture.php", $sections, $gallerySection, $view, $styles, $scripts);
	}
}
?>
