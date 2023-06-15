<?php
namespace SBGallery\Model\Settings\URLGenerator;
use SBCrud\Model\RouteUtils;

class LayoutAlbumURLGenerator extends LayoutPictureURLGenerator implements AlbumURLGenerator
{
	public function __construct(string $operationParam = "__operation")
	{
		parent::__construct($operationParam);
	}

	public function generateAlbumPageURL(string $albumId, int $page, string $argSeparator): string
	{
		return RouteUtils::composeSelfURLWithParameters($argSeparator, "", array("albumPage" => $page));
	}

	public function generatePictureURL(string $albumId, string $pictureId, string $argSeparator, array $extraGetParameters = array()): string
	{
		return RouteUtils::composeSelfURLWithParameters($argSeparator, "/".rawurlencode($pictureId), $extraGetParameters);
	}

	public function generateAddPictureURL(string $albumId, string $argSeparator): string
	{
		return RouteUtils::composeSelfURLWithParameters($argSeparator, "", array($this->operationParam => "create_picture"));
	}

	public function generateAddMultiplePicturesURL(string $albumId, string $argSeparator): string
	{
		return RouteUtils::composeSelfURLWithParameters($argSeparator, "", array($this->operationParam => "add_multiple_pictures"));
	}

	public function generateMovePictureLeftURL(int $id, string $albumId, string $pictureId, string $argSeparator): string
	{
		return $this->generatePictureURL($albumId, $pictureId, $argSeparator, array(
			$this->operationParam => "moveleft_picture",
			"__id" => $id
		));
	}

	public function generateMovePictureRightURL(int $id, string $albumId, string $pictureId, string $argSeparator): string
	{
		return $this->generatePictureURL($albumId, $pictureId, $argSeparator, array(
			$this->operationParam => "moveright_picture",
			"__id" => $id
		));
	}

	public function generateSetAsThumbnailURL(int $id, string $albumId, string $pictureId, string $argSeparator): string
	{
		return $this->generatePictureURL($albumId, $pictureId, $argSeparator, array(
			$this->operationParam => "setasthumbnail_picture",
			"__id" => $id
		));
	}

	public function generateRemovePictureURL(int $id, string $albumId, string $pictureId, string $argSeparator): string
	{
		return $this->generatePictureURL($albumId, $pictureId, $argSeparator, array(
			$this->operationParam => "remove_picture",
			"__id" => $id
		));
	}

	public function generateAlbumFormURL(?string $albumId, string $argSeparator): ?string
	{
		return RouteUtils::composeSelfURLWithParameters($argSeparator);
	}

	public function generatePicturesUploaderFormURL(string $albumId, string $argSeparator): string
	{
		return RouteUtils::composeSelfURLWithParameters($argSeparator);
	}
}
?>
