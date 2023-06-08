<?php
namespace SBGallery\Model\Page\Settings;
use SBLayout\Model\Page\Page;
use SBGallery\Model\Settings\GallerySettings;
use SBGallery\Model\Settings\EditorSettings;
use SBGallery\Model\Settings\Labels\GalleryLabels;
use SBGallery\Model\Settings\Labels\AlbumLabels;
use SBGallery\Model\Settings\Labels\PictureLabels;
use SBGallery\Model\Settings\URLGenerator\LayoutGalleryURLGenerator;
use SBGallery\Model\Page\Settings\Labels\AlbumPageLabels;
use SBGallery\Model\Page\Settings\Labels\GalleryPageLabels;
use SBGallery\Model\Page\Settings\Labels\PicturePageLabels;

/**
 * Contains all possible configuration options of a gallery page.
 */
class GalleryPageSettings extends AlbumPageSettings
{
	public GallerySettings $gallerySettings;

	public GalleryPageLabels $galleryPageLabels;

	public ?string $galleryPageMenuItem;

	public function __construct(string $baseURL,
		string $baseDir = "gallery",
		int $thumbnailWidth = 160,
		int $thumbnailHeight = 160,
		int $pictureWidth = 1280,
		int $pictureHeight = 1280,
		int $filePermissions = 0666,
		int $dirPermissions = 0777,
		bool $displayAnchors = true,
		string $iconsPath = "image/gallery",
		string $albumAnchorPrefix = "album",
		string $pictureAnchorPrefix = "picture",
		GalleryPageLabels $galleryPageLabels = null,
		AlbumPageLabels $albumPageLabels = null,
		PicturePageLabels $picturePageLabels = null,
		GalleryLabels $galleryLabels = null,
		AlbumLabels $albumLabels = null,
		PictureLabels $pictureLabels = null,
		EditorSettings $albumEditorSettings = null,
		EditorSettings $pictureEditorSettings = null,
		string $albumEditorLabelsFile = null,
		string $pictureEditorLabelsFile = null,
		string $albumsTable = "albums",
		string $thumbnailsTable = "thumbnails",
		string $picturesTable = "pictures",
		string $operationParam = "__operation",
		string $galleryPageMenuItem = null,
		string $albumPageMenuItem = null)
	{
		parent::__construct($albumPageLabels, $picturePageLabels, $albumEditorLabelsFile, $pictureEditorLabelsFile, $albumPageMenuItem);

		$pageBaseURL = Page::computeBaseURL();

		// If editor label files were provided, then also auto assign a label parameter
		if($albumEditorLabelsFile !== null)
			$albumEditorSettings->labelsParameter = "albumEditorLabels";

		if($pictureEditorLabelsFile !== null)
			$pictureEditorSettings->labelsParameter = "pictureEditorLabels";

		$this->gallerySettings = new GallerySettings(new LayoutGalleryURLGenerator(),
			$pageBaseURL."/".$baseURL,
			$baseDir,
			$thumbnailWidth,
			$thumbnailHeight,
			$pictureWidth,
			$pictureHeight,
			$filePermissions,
			$dirPermissions,
			$displayAnchors,
			$pageBaseURL."/".$iconsPath,
			$albumAnchorPrefix,
			$pictureAnchorPrefix,
			$galleryLabels,
			$albumLabels,
			$pictureLabels,
			$albumEditorSettings,
			$pictureEditorSettings,
			$albumsTable,
			$thumbnailsTable,
			$picturesTable,
			$operationParam);

		if($galleryPageLabels === null)
			$this->galleryPageLabels = new GalleryPageLabels();
		else
			$this->galleryPageLabels = $galleryPageLabels;

		$this->galleryPageMenuItem = $galleryPageMenuItem;
	}
}
?>
