<?php
namespace SBGallery\Model\Settings;
use SBGallery\Model\Settings\Labels\AlbumLabels;
use SBGallery\Model\Settings\Labels\PictureLabels;
use SBGallery\Model\Settings\URLGenerator\AlbumURLGenerator;

/**
 * Contains all possible configuration options of an album and its related objects: pictures
 */
class AlbumSettings
{
	/** An object providing methods that compose URLs to the sub pages of the album */
	public AlbumURLGenerator $urlGenerator;

	/** Base URL of the gallery */
	public string $baseURL;

	/** Base directory in which all gallery artifacts are stored */
	public string $baseDir;

	/** Maximum width of a thumbnail */
	public int $thumbnailWidth;

	/** Maximum height of a thumbnail */
	public int $thumnailHeight;

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

	/** Specifies the prefix of the anchor elements that come in front of each picture thumbnail */
	public string $anchorPrefix;

	/** Object containing all the labels of the album pages */
	public AlbumLabels $albumLabels;

	/** Object containing all the labels of the picture pages */
	public PictureLabels $pictureLabels;

	/** An object containing the editor settings for the album pages */
	public EditorSettings $albumEditorSettings;

	/** An object containing the editor settings for the picture pages */
	public EditorSettings $pictureEditorSettings;

	/** The amount of picture thumbnails displayed per album page. null means there is no limit */
	public ?int $pageSize;

	/** Name of the thumbnails table */
	public string $thumbnailsTable;

	/** Name of the pictures table */
	public string $picturesTable;

	/** Name of the operation parameter */
	public string $operationParam;

	/**
	 * Constructs a new album settings instance.
	 *
	 * @param $urlGenerator An object providing methods that compose URLs to the sub pages of the album
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
	 * @param $anchorPrefix Specifies the prefix of the anchor elements that come in front of each picture thumbnail
	 * @param $albumLabels Object containing all the labels of the album pages
	 * @param $pictureLabels Object containing all the labels of the picture pages
	 * @param $albumEditorSettings An object containing the editor settings for the album pages
	 * @param $pictureEditorSettings An object containing the editor settings for the picture pages
	 * @param $thumbnailsTable Name of the thumbnails table
	 * @param $picturesTable Name of the pictures table
	 * @param $operationParam Name of the operation parameter
	 */
	public function __construct(AlbumURLGenerator $urlGenerator,
		string $baseURL,
		string $baseDir,
		int $thumbnailWidth = 160,
		int $thumbnailHeight = 160,
		int $pictureWidth = 1280,
		int $pictureHeight = 1280,
		int $filePermissions = 0666,
		int $dirPermissions = 0777,
		bool $displayAnchors = true,
		string $iconsPath = "image/gallery",
		string $anchorPrefix = "picture",
		AlbumLabels $albumLabels = null,
		PictureLabels $pictureLabels = null,
		EditorSettings $albumEditorSettings = null,
		EditorSettings $pictureEditorSettings = null,
		int $pageSize = null,
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
		$this->anchorPrefix = $anchorPrefix;

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

		$this->pageSize = $pageSize;
		$this->thumbnailsTable = $thumbnailsTable;
		$this->picturesTable = $picturesTable;
		$this->operationParam = $operationParam;
	}

	/**
	 * Constructs a picture settings object from the album settings.
	 *
	 * @param $albumId ID of the album in which the pictures are stored
	 * @return A picture settings object inheriting the relevant album settings
	 */
	public function constructPictureSettings(string $albumId): PictureSettings
	{
		return new PictureSettings($this->urlGenerator, $this->baseURL."/".rawurlencode($albumId), $this->iconsPath, $this->pictureLabels, $this->pictureEditorSettings, $this->picturesTable, $this->operationParam);
	}
}
?>
