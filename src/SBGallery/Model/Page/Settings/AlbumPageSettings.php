<?php
namespace SBGallery\Model\Page\Settings;
use SBGallery\Model\Page\Settings\Labels\AlbumPageLabels;
use SBGallery\Model\Page\Settings\Labels\PicturePageLabels;

/**
 * Contains all possible configuration options of an album page.
 */
class AlbumPageSettings extends PicturePageSettings
{
	public AlbumPageLabels $albumPageLabels;

	public ?string $albumMenuItem;

	public function __construct(AlbumPageLabels $albumPageLabels = null, PicturePageLabels $picturePageLabels = null, string $albumMenuItem = null)
	{
		parent::__construct($picturePageLabels);

		if($albumPageLabels === null)
			$this->albumPageLabels = new AlbumPageLabels();
		else
			$this->albumPageLabels = $albumPageLabels;

		if($albumMenuItem === null)
			$this->albumMenuItem = dirname(__FILE__)."/../../../View/HTML/menuitems/album.php";
		else
			$this->albumMenuItem = $albumMenuItem;
	}
}
?>
