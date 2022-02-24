<?php
namespace SBGallery\Model\Page\Util;

function composeGalleryContents(?array $sections, string $gallerySection, string $path)
{
	if($sections === null)
		$contentSections = array();
	else
		$contentSections = $sections;
	
	$contentSections[$gallerySection] = $path;
	return $contentSections;
}
?>
