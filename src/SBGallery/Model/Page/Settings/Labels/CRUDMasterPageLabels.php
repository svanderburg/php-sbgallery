<?php
namespace SBGallery\Model\Page\Settings\Labels;

/**
 * Collects all labels that all gallery pages have in common
 */
class CRUDMasterPageLabels
{
	public string $title;

	public string $invalidQueryParameterMessage;

	public string $invalidOperationMessage;

	public function __construct(string $title = "Gallery",
		string $invalidQueryParameterMessage = "Invalid album with identifier:",
		string $invalidOperationMessage = "Invalid operation:")
	{
		$this->title = $title;
		$this->invalidQueryParameterMessage = $invalidQueryParameterMessage;
		$this->invalidOperationMessage = $invalidOperationMessage;
	}
}
?>
