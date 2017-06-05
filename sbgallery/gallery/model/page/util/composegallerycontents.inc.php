<?php
function composeGalleryContents($sections, $gallerySection, $path)
{
	if($sections === null)
		$contentSections = array();
	else
		$contentSections = $sections;
	
	$contentSection[$gallerySection] = $path;
	return $contentSection;
}
?>
