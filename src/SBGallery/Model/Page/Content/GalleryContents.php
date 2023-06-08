<?php
namespace SBGallery\Model\Page\Content;

class GalleryContents extends OverridedContents
{
	public function __construct(array $sections = null, string $gallerySection = "contents", string $view = "HTML", ?array $styles = array(), ?array $scripts = array(), string $sectionFile = null)
	{
		if($sectionFile === null)
		{
			$sectionFile = "gallery.php";
			$relativeSectionFile = true;
		}
		else
			$relativeSectionFile = false;

		parent::__construct($sectionFile, $relativeSectionFile, null, $sections, $gallerySection, $view, $styles, $scripts);
	}

	public function constructAlbumContents(string $albumEditorLabelsFile = null): AlbumContents
	{
		return new AlbumContents($albumEditorLabelsFile, $this->sections, $this->gallerySection, $this->view, $this->styles, $this->scripts);
	}
}
?>
