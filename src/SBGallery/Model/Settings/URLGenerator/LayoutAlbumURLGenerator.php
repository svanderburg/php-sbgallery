<?php
namespace SBGallery\Model\Settings\URLGenerator;
use SBCrud\Model\RouteUtils;

class LayoutAlbumURLGenerator extends LayoutPictureURLGenerator implements AlbumURLGenerator
{
	public function __construct(string $operationParam = "__operation")
	{
		parent::__construct($operationParam);
	}

	public function generatePictureURL(string $albumId, string $pictureId): string
	{
		return RouteUtils::composeSelfURL()."/".rawurlencode($pictureId);
	}

	public function generateAddPictureURL(string $albumId): string
	{
		return "?".$this->operationParam."=create_picture";
	}

	public function generateAddMultiplePicturesURL(string $albumId): string
	{
		return "?".$this->operationParam."=add_multiple_pictures";
	}

	public function generateMovePictureLeftURL(int $id, string $albumId, string $pictureId): string
	{
		return $this->generatePictureURL($albumId, $pictureId)."?".$this->operationParam."=moveleft_picture&amp;__id=".$id;
	}

	public function generateMovePictureRightURL(int $id, string $albumId, string $pictureId): string
	{
		return $this->generatePictureURL($albumId, $pictureId)."?".$this->operationParam."=moveright_picture&amp;__id=".$id;
	}

	public function generateSetAsThumbnailURL(int $id, string $albumId, string $pictureId): string
	{
		return $this->generatePictureURL($albumId, $pictureId)."?".$this->operationParam."=setasthumbnail_picture&amp;__id=".$id;
	}

	public function generateRemovePictureURL(int $id, string $albumId, string $pictureId): string
	{
		return $this->generatePictureURL($albumId, $pictureId)."?".$this->operationParam."=remove_picture&amp;__id=".$id;
	}

	public function generateAlbumFormURL(?string $albumId): ?string
	{
		return RouteUtils::composeSelfURL();
	}

	public function generatePicturesUploaderFormURL(string $albumId): string
	{
		return RouteUtils::composeSelfURL();
	}
}
?>
