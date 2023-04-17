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

	private function generatePreviousOrNextPictureURL(string $albumId, string $pictureId): string
	{
		return rawurlencode($pictureId);
	}

	public function generatePreviousPictureURL(string $albumId, string $pictureId): string
	{
		return $this->generatePreviousOrNextPictureURL($albumId, $pictureId);
	}

	public function generateNextPictureURL(string $albumId, string $pictureId): string
	{
		return $this->generatePreviousOrNextPictureURL($albumId, $pictureId);
	}

	public function generatePictureFormURL(string $albumId, ?string $pictureId): ?string
	{
		return RouteUtils::composeSelfURL();
	}

	public function generateClearPictureURL(string $albumId, string $pictureId): string
	{
		return "?".$this->operationParam."=clear_picture";
	}
}
?>
