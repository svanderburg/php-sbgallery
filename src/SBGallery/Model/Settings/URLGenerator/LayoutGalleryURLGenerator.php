<?php
namespace SBGallery\Model\Settings\URLGenerator;
use SBCrud\Model\RouteUtils;

class LayoutGalleryURLGenerator extends LayoutAlbumURLGenerator implements GalleryURLGenerator
{
	public function __construct(string $operationParam = "__operation")
	{
		parent::__construct($operationParam);
	}

	public function generateAlbumURL(string $albumId): string
	{
		return RouteUtils::composeSelfURL()."/".rawurlencode($albumId);
	}

	public function generateAddAlbumURL(): string
	{
		return "?".$this->operationParam."=create_album";
	}

	public function generateRemoveAlbumURL(int $id, string $albumId): string
	{
		return $this->generateAlbumURL($albumId)."?".$this->operationParam."=remove_album&amp;__id=".$id;
	}

	public function generateMoveAlbumLeftURL(int $id, string $albumId): string
	{
		return $this->generateAlbumURL($albumId)."?".$this->operationParam."=moveleft_album&amp;__id=".$id;
	}

	public function generateMoveAlbumRightURL(int $id, string $albumId): string
	{
		return $this->generateAlbumURL($albumId)."?".$this->operationParam."=moveright_album&amp;__id=".$id;
	}
}
?>
