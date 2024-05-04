<?php
namespace SBGallery\Model\Settings;
use SBGallery\Model\Settings\Labels\GalleryLabels;
use SBGallery\Model\Settings\Labels\AlbumLabels;
use SBGallery\Model\Settings\Labels\PictureLabels;
use SBGallery\Model\Settings\URLGenerator\GalleryURLGenerator;

/**
 * Contains all possible configuration options of a gallery and its related objects: albums and pictures
 */
class GallerySettings
{
	/** An object providing methods that compose URLs to the sub pages of the gallery */
	public GalleryURLGenerator $urlGenerator;

	/** Base URL of the gallery */
	public string $baseURL;

	/** Base directory in which all gallery artifacts are stored */
	public string $baseDir;

	/** Maximum width of a thumbnail */
	public int $thumbnailWidth;

	/** Maximum height of a thumbnail */
	public int $thumbnailHeight;

	/** Maximum width of a picture */
	public int $pictureWidth;

	/** Maximum height of a picture */
	public int $pictureHeight;

	/** Contains the permission bits of an image file */
	public int $filePermissions;

	/** Contains the permission bits of a directory in the gallery folder */
	public int $dirPermissions;

	/** Specifies whether to display anchors to each album or picture thumbnail */
	public bool $displayAnchors;

	/** Contains the root directory of the gallery icons */
	public string $iconsPath;

	/** Specifies the prefix of the anchor elements that come in front of each album thumbnail */
	public string $albumAnchorPrefix;

	/** Specifies the prefix of the anchor elements that come in front of each picture thumbnail */
	public string $pictureAnchorPrefix;

	/** Object containing all the labels of the gallery pages */
	public GalleryLabels $galleryLabels;

	/** Object containing all the labels of the album pages */
	public AlbumLabels $albumLabels;

	/** Object containing all the labels of the picture pages */
	public PictureLabels $pictureLabels;

	/** An object containing the editor settings for the album pages */
	public EditorSettings $albumEditorSettings;

	/** An object containing the editor settings for the picture pages */
	public EditorSettings $pictureEditorSettings;

	/** The amount of album thumbnails displayed per gallery page. null means there is no limit */
	public ?int $galleryPageSize;

	/** The amount of picture thumbnails displayed per album page. null means there is no limit */
	public ?int $albumPageSize;

	/** Name of the albums table */
	public string $albumsTable;

	/** Name of the thumbnails table */
	public string $thumbnailsTable;

	/** Name of the pictures table */
	public string $picturesTable;

	/** Name of the operation parameter */
	public string $operationParam;

	/**
	 * Constructs a new gallery settings instance.
	 *
	 * @param $urlGenerator An object providing methods that compose URLs to the sub pages of the gallery
	 * @param $baseURL Base URL of the gallery
	 * @param $baseDir Base directory in which all gallery artifacts are stored
	 * @param $thumbnailWidth Maximum width of a thumbnail
	 * @param $thumbnailHeight Maximum height of a thumbnail
	 * @param $pictureWidth Maximum width of a picture
	 * @param $pictureHeight Maximum height of a picture
	 * @param $filePermissions Contains the permission bits of an image file
	 * @param $dirPermissions Contains the permission bits of a directory in the gallery folder
	 * @param $displayAnchors Specifies whether to display anchors to each album or picture thumbnail
	 * @param $iconsPath Contains the root directory of the gallery icons
	 * @param $albumAnchorPrefix Specifies the prefix of the anchor elements that come in front of each album thumbnail
	 * @param $pictureAnchorPrefix Specifies the prefix of the anchor elements that come in front of each picture thumbnail
	 * @param $galleryLabels Object containing all the labels of the gallery pages
	 * @param $albumLabels Object containing all the labels of the album pages
	 * @param $pictureLabels Object containing all the labels of the picture pages
	 * @param $albumEditorSettings An object containing the editor settings for the album pages
	 * @param $pictureEditorSettings An object containing the editor settings for the picture pages
	 * @param $galleryPageSize The amount of album thumbnails displayed per gallery page
	 * @param $picturePageSize The amount of picture thumbnails displayed per album page
	 * @param $albumsTable Name of the albums table
	 * @param $thumbnailsTable Name of the thumbnails table
	 * @param $picturesTable Name of the pictures table
	 * @param $operationParam Name of the operation parameter
	 */
	public function __construct(GalleryURLGenerator $urlGenerator,
		string $baseURL,
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
		GalleryLabels $galleryLabels = null,
		AlbumLabels $albumLabels = null,
		PictureLabels $pictureLabels = null,
		EditorSettings $albumEditorSettings = null,
		EditorSettings $pictureEditorSettings = null,
		int $galleryPageSize = null,
		int $albumPageSize = null,
		string $albumsTable = "albums",
		string $thumbnailsTable = "thumbnails",
		string $picturesTable = "pictures",
		string $operationParam = "__operation")
	{
		$this->urlGenerator = $urlGenerator;
		$this->baseURL = $baseURL;
		$this->baseDir = $baseDir;
		$this->thumbnailWidth = $thumbnailWidth;
		$this->thumbnailHeight = $thumbnailHeight;
		$this->pictureWidth = $pictureWidth;
		$this->pictureHeight = $pictureHeight;
		$this->filePermissions = $filePermissions;
		$this->dirPermissions = $dirPermissions;
		$this->displayAnchors = $displayAnchors;
		$this->iconsPath = $iconsPath;
		$this->albumAnchorPrefix = $albumAnchorPrefix;
		$this->pictureAnchorPrefix = $pictureAnchorPrefix;

		if($galleryLabels === null)
			$this->galleryLabels = new GalleryLabels();
		else
			$this->galleryLabels = $galleryLabels;

		if($albumLabels === null)
			$this->albumLabels = new AlbumLabels();
		else
			$this->albumLabels = $albumLabels;

		if($pictureLabels === null)
			$this->pictureLabels = new PictureLabels();
		else
			$this->pictureLabels = $pictureLabels;

		if($albumEditorSettings === null)
			$this->albumEditorSettings = new EditorSettings();
		else
			$this->albumEditorSettings = $albumEditorSettings;

		if($pictureEditorSettings === null)
			$this->pictureEditorSettings = new EditorSettings();
		else
			$this->pictureEditorSettings = $pictureEditorSettings;

		$this->galleryPageSize = $galleryPageSize;
		$this->albumPageSize = $albumPageSize;
		$this->albumsTable = $albumsTable;
		$this->thumbnailsTable = $thumbnailsTable;
		$this->picturesTable = $picturesTable;
		$this->operationParam = $operationParam;
	}

	/**
	 * Constructs an album settings object from the gallery settings.
	 *
	 * @return An album settings object inheriting the relevant gallery settings
	 */
	public function constructAlbumSettings(): AlbumSettings
	{
		return new AlbumSettings($this->urlGenerator,
			$this->baseURL,
			$this->baseDir,
			$this->thumbnailWidth,
			$this->thumbnailHeight,
			$this->pictureWidth,
			$this->pictureHeight,
			$this->filePermissions,
			$this->dirPermissions,
			$this->displayAnchors,
			$this->iconsPath,
			$this->pictureAnchorPrefix,
			$this->albumLabels,
			$this->pictureLabels,
			$this->albumEditorSettings,
			$this->pictureEditorSettings,
			$this->albumPageSize,
			$this->thumbnailsTable,
			$this->picturesTable,
			$this->operationParam);
	}
}
?>
