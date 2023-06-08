<?php
namespace SBGallery\Model\Page\Content;
use SBLayout\Model\Page\Page;

class AlbumContents extends OverridedContents
{
	public function __construct(string $albumEditorLabelsFile = null, array $sections = null, string $gallerySection = "contents", string $view = "HTML", ?array $styles = array(), ?array $scripts = array())
	{
		$htmlEditorJsPath = Page::computeBaseURL()."/scripts/htmleditor.js";
		array_push($scripts, $htmlEditorJsPath);

		if($albumEditorLabelsFile !== null)
			array_push($scripts, $albumEditorLabelsFile);

		parent::__construct("gallery/album.php", true, "gallery/album.php", $sections, $gallerySection, $view, $styles, $scripts);
	}

	public function constructPictureContents(string $pictureEditorLabelsFile = null): PictureContents
	{
		return new PictureContents($pictureEditorLabelsFile, $this->sections, $this->gallerySection, $this->view, $this->styles, $this->scripts);
	}

	public function constructMultiplePictureContents(): OverridedContents
	{
		return new OverridedContents("gallery/picturesuploader.php", true, null, $this->sections, $this->gallerySection, $this->view, $this->styles, $this->scripts);
	}
}
?>
