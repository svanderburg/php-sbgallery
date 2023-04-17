<?php
namespace SBGallery\Model\Settings;
use SBGallery\Model\Settings\Labels\PictureLabels;
use SBGallery\Model\Settings\URLGenerator\PictureURLGenerator;

/**
 * Contains all possible configuration options of a picture
 */
class PictureSettings
{
	/** An object providing methods that compose URLs to the sub pages of a picture */
	public PictureURLGenerator $urlGenerator;

	/** Base URL of the album */
	public string $baseURL;

	/** Contains the root directory of the gallery icons */
	public string $iconsPath;

	/** Object containing all the labels of the picture pages */
	public PictureLabels $labels;

	/** An object containing the editor settings for the picture pages */
	public EditorSettings $editorSettings;

	/** Name of the pictures table */
	public string $picturesTable;

	/** Name of the operation parameter */
	public string $operationParam;

	/**
	 * Constructs a new picture settings instance.
	 *
	 * @param $urlGenerator An object providing methods that compose URLs to the sub pages of a picture
	 * @param $baseURL Base URL of the album
	 * @param $iconsPath Contains the root directory of the gallery icons
	 * @param $labels Object containing all the labels of the picture pages
	 * @param $editorSettings An object containing the editor settings for the picture pages
	 * @param $picturesTable Name of the pictures table
	 * @param $operationParam Name of the operation parameter
	 */
	public function __construct(PictureURLGenerator $urlGenerator,
		string $baseURL,
		string $iconsPath = "image/gallery",
		PictureLabels $labels = null,
		EditorSettings $editorSettings = null,
		string $picturesTable = "pictures",
		string $operationParam = "__operation")
	{
		$this->urlGenerator = $urlGenerator;
		$this->baseURL = $baseURL;
		$this->iconsPath = $iconsPath;
		$this->picturesTable = $picturesTable;

		if($labels === null)
			$this->labels = new PictureLabels();
		else
			$this->labels = $labels;

		if($editorSettings === null)
			$this->editorSettings = new EditorSettings();
		else
			$this->editorSettings = $editorSettings;

		$this->operationParam = $operationParam;
	}
}
?>
