<?php
namespace SBGallery\Model\Settings\URLGenerator;
use SBCrud\Model\RouteUtils;

class LayoutPictureURLGenerator implements PictureURLGenerator
{
	public string $operationParam;

	public function __construct(string $operationParam = "__operation")
	{
		$this->operationParam = $operationParam;
	}

	private function generatePreviousOrNextPictureURL(string $albumId, string $pictureId, string $argSeparator): string
	{
		if(array_key_exists("requestParameters", $GLOBALS) && count($GLOBALS["requestParameters"]) > 0)
			$extraParameters = "?".http_build_query($GLOBALS["requestParameters"], "", $argSeparator, PHP_QUERY_RFC3986);
		else
			$extraParameters = "";

		return rawurlencode($pictureId).$extraParameters;
	}

	public function generatePreviousPictureURL(string $albumId, string $pictureId, string $argSeparator): string
	{
		return $this->generatePreviousOrNextPictureURL($albumId, $pictureId, $argSeparator);
	}

	public function generateNextPictureURL(string $albumId, string $pictureId, string $argSeparator): string
	{
		return $this->generatePreviousOrNextPictureURL($albumId, $pictureId, $argSeparator);
	}

	public function generatePictureFormURL(string $albumId, ?string $pictureId, string $argSeparator): ?string
	{
		return RouteUtils::composeSelfURLWithParameters($argSeparator);
	}

	public function generateClearPictureURL(string $albumId, string $pictureId, string $argSeparator): string
	{
		return RouteUtils::composeSelfURLWithParameters($argSeparator, "", array($this->operationParam => "clear_picture"));
	}
}
?>
