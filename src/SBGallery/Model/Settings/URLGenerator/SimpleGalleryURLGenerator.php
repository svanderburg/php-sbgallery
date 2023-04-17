<?php
namespace SBGallery\Model\Settings\URLGenerator;

class SimpleGalleryURLGenerator extends SimpleAlbumURLGenerator implements GalleryURLGenerator
{
	public function __construct(string $operationParam = "__operation")
	{
		parent::__construct($operationParam);
	}

	public function generateAlbumURL(string $albumId): string
	{
		return $this->generateAddAlbumURL()."?ALBUM_ID=".rawurlencode($albumId);
	}

	public function generateAddAlbumURL(): string
	{
		return "album.php";
	}

	public function generateRemoveAlbumURL(int $id, string $albumId): string
	{
		return $this->generateAlbumURL($albumId)."&amp;".$this->operationParam."=remove_album&amp;__id=".$id;
	}

	public function generateMoveAlbumLeftURL(int $id, string $albumId): string
	{
		return $this->generateAlbumURL($albumId)."&amp;".$this->operationParam."=moveleft_album&amp;__id=".$id;
	}

	public function generateMoveAlbumRightURL(int $id, string $albumId): string
	{
		return $this->generateAlbumURL($albumId)."&amp;".$this->operationParam."=moveright_album&amp;__id=".$id;
	}
}
?>
