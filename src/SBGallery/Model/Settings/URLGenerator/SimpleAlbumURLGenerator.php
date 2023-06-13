<?php
namespace SBGallery\Model\Settings\URLGenerator;

class SimpleAlbumURLGenerator extends SimplePictureURLGenerator implements AlbumURLGenerator
{
	public function __construct(string $operationParam = "__operation")
	{
		parent::__construct($operationParam);
	}

	public function generateAlbumPageURL(string $albumId, int $page, string $argSeparator): string
	{
		return "album.php?ALBUM_ID=".rawurlencode($albumId).$argSeparator."page=".$page;
	}

	public function generatePictureURL(string $albumId, string $pictureId, string $argSeparator): string
	{
		return $this->generateAddPictureURL($albumId, $argSeparator).$argSeparator."PICTURE_ID=".rawurlencode($pictureId);
	}

	public function generateAddPictureURL(string $albumId, string $argSeparator): string
	{
		return "picture.php?ALBUM_ID=".rawurlencode($albumId);
	}

	public function generateAddMultiplePicturesURL(string $albumId, string $argSeparator): string
	{
		return "picturesuploader.php?ALBUM_ID=".rawurlencode($albumId);
	}

	public function generateMovePictureLeftURL(int $id, string $albumId, string $pictureId, string $argSeparator): string
	{
		return $this->generatePictureURL($albumId, $pictureId, $argSeparator).$argSeparator.$this->operationParam."=moveleft_picture".$argSeparator."__id=".$id;
	}

	public function generateMovePictureRightURL(int $id, string $albumId, string $pictureId, string $argSeparator): string
	{
		return $this->generatePictureURL($albumId, $pictureId, $argSeparator).$argSeparator.$this->operationParam."=moveright_picture".$argSeparator."__id=".$id;
	}

	public function generateSetAsThumbnailURL(int $id, string $albumId, string $pictureId, string $argSeparator): string
	{
		return $this->generatePictureURL($albumId, $pictureId, $argSeparator).$argSeparator.$this->operationParam."=setasthumbnail_picture".$argSeparator."__id=".$id;
	}

	public function generateRemovePictureURL(int $id, string $albumId, string $pictureId, string $argSeparator): string
	{
		return $this->generatePictureURL($albumId, $pictureId, $argSeparator).$argSeparator.$this->operationParam."=remove_picture".$argSeparator."__id=".$id;
	}

	public function generateAlbumFormURL(?string $albumId, string $argSeparator): ?string
	{
		$result = "album.php";

		if($albumId !== null)
			$result .= "?ALBUM_ID=".rawurlencode($albumId);

		return $result;
	}

	public function generatePicturesUploaderFormURL(string $albumId, string $argSeparator): string
	{
		return "picturesuploader.php";
	}
}
?>
