<?php
namespace SBGallery\Model\Settings\URLGenerator;
use SBCrud\Model\RouteUtils;

class LayoutGalleryURLGenerator extends LayoutAlbumURLGenerator implements GalleryURLGenerator
{
	public function __construct(string $operationParam = "__operation")
	{
		parent::__construct($operationParam);
	}

	public function generateGalleryPageURL(int $page, string $argSeparator): string
	{
		return RouteUtils::composeSelfURLWithParameters($argSeparator, "", array("galleryPage" => $page));
	}

	public function generateAlbumURL(string $albumId, string $argSeparator, array $extraGetParameters = array()): string
	{
		return RouteUtils::composeSelfURLWithParameters($argSeparator, "/".rawurlencode($albumId), $extraGetParameters);
	}

	public function generateAddAlbumURL(string $argSeparator): string
	{
		return RouteUtils::composeSelfURLWithParameters($argSeparator, "", array($this->operationParam => "create_album"));
	}

	public function generateRemoveAlbumURL(int $id, string $albumId, string $argSeparator): string
	{
		return $this->generateAlbumURL($albumId, $argSeparator, array(
			$this->operationParam => "remove_album",
			"__id" => $id
		));
	}

	public function generateMoveAlbumLeftURL(int $id, string $albumId, string $argSeparator): string
	{
		return $this->generateAlbumURL($albumId, $argSeparator, array(
			$this->operationParam => "moveleft_album",
			"__id" => $id
		));
	}

	public function generateMoveAlbumRightURL(int $id, string $albumId, string $argSeparator): string
	{
		return $this->generateAlbumURL($albumId, $argSeparator, array(
			$this->operationParam => "moveright_album",
			"__id" => $id
		));
	}
}
?>
