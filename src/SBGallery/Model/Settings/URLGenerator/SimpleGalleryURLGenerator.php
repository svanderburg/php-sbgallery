<?php
namespace SBGallery\Model\Settings\URLGenerator;

class SimpleGalleryURLGenerator extends SimpleAlbumURLGenerator implements GalleryURLGenerator
{
	public function __construct(string $operationParam = "__operation")
	{
		parent::__construct($operationParam);
	}

	public function generateGalleryPageURL(int $page, string $argSeparator): string
	{
		return "gallery.php?PAGE_ID=".$page;
	}

	public function generateAlbumURL(string $albumId, string $argSeparator): string
	{
		return $this->generateAddAlbumURL($argSeparator)."?ALBUM_ID=".rawurlencode($albumId);
	}

	public function generateAddAlbumURL(string $argSeparator): string
	{
		return "album.php";
	}

	public function generateRemoveAlbumURL(int $id, string $albumId, string $argSeparator): string
	{
		return $this->generateAlbumURL($albumId, $argSeparator).$argSeparator.$this->operationParam."=remove_album".$argSeparator."__id=".$id;
	}

	public function generateMoveAlbumLeftURL(int $id, string $albumId, string $argSeparator): string
	{
		return $this->generateAlbumURL($albumId, $argSeparator).$argSeparator.$this->operationParam."=moveleft_album".$argSeparator."__id=".$id;
	}

	public function generateMoveAlbumRightURL(int $id, string $albumId, string $argSeparator): string
	{
		return $this->generateAlbumURL($albumId, $argSeparator).$argSeparator.$this->operationParam."=moveright_album".$argSeparator."__id=".$id;
	}
}
?>
