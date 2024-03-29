<?php
namespace SBGallery\Model\Page\Settings;
use SBGallery\Model\Page\Settings\Labels\PicturePageLabels;

/**
 * Contains all possible configuration options of a picture page.
 */
class PicturePageSettings
{
	public PicturePageLabels $picturePageLabels;

	public ?string $pictureEditorLabelsFile;

	public function __construct(PicturePageLabels $picturePageLabels = null, string $pictureEditorLabelsFile = null)
	{
		if($picturePageLabels === null)
			$this->picturePageLabels = new PicturePageLabels();
		else
			$this->picturePageLabels = $picturePageLabels;

		$this->pictureEditorLabelsFile = $pictureEditorLabelsFile;
	}
}
?>
