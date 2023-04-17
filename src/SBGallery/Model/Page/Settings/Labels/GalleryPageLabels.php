<?php
namespace SBGallery\Model\Page\Settings\Labels;

/**
 * Collects all labels that a gallery page displays
 */
class GalleryPageLabels extends CRUDMasterPageLabels
{
	public string $title;

	public string $invalidQueryParameterMessage;

	public string $invalidOperationMessage;

	public function __construct(string $title = "Gallery",
		string $invalidQueryParameterMessage = "Invalid album with identifier:",
		string $invalidOperationMessage = "Invalid operation:")
	{
		parent::__construct($title, $invalidQueryParameterMessage, $invalidOperationMessage);
	}
}
?>
