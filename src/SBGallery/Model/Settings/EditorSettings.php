<?php
namespace SBGallery\Model\Settings;
use SBLayout\Model\Page\Page;

/**
 * Contains all possible configuration values of an editor field
 */
class EditorSettings
{
	public string $id;

	public string $iframePage;

	public string $iconsPath;

	public int $width;

	public int $height;

	public function __construct(string $id = "editor1", string $iframePage = "iframepage.html", string $iconsPath = "image/editor", int $width = 60, int $height = 20)
	{
		$pageBaseURL = Page::computeBaseURL();

		$this->id = $id;
		$this->iframePage = $iframePage;
		$this->iconsPath = $pageBaseURL."/".$iconsPath;
		$this->width = $width;
		$this->height = $height;
	}
}
?>
