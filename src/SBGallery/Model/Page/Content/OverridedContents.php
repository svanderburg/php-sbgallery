<?php
namespace SBGallery\Model\Page\Content;
use SBLayout\Model\Page\Content\Contents;

class OverridedContents extends Contents
{
	public string $gallerySection;

	public string $view;

	public function __construct(string $sectionFile, bool $relativeSectionFile = true, string $controller = null, array $sections = null, string $gallerySection = "contents", string $view = "HTML", ?array $styles = array(), ?array $scripts = array())
	{
		if($sections === null)
			$sections = array();

		if($relativeSectionFile)
			$contentsPath = dirname(__FILE__)."/../../../View/".$view."/contents/";
		else
			$contentsPath = "";

		$sections[$gallerySection] = $contentsPath.$sectionFile;

		if($controller !== null)
			$controller = dirname(__FILE__)."/controller/".$controller;

		parent::__construct($sections, $controller, $styles, $scripts);

		// These values become properties of the object so that we construct new Contents object from it
		$this->gallerySection = $gallerySection;
		$this->view = $view;
	}
}
?>
