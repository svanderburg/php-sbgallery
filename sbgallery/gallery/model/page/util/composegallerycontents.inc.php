<?php
function composeGalleryContents($sections, $gallerySection, $path)
{
	if($sections === null)
		$contentSections = array();
	else
		$contentSections = $sections;
	
	$contentSections[$gallerySection] = $path;
	return $contentSections;
}
?>
