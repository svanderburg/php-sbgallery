<?php
namespace SBGallery\Model\Settings\URLGenerator;

class SimpleAlbumURLGenerator extends SimplePictureURLGenerator implements AlbumURLGenerator
{
	public function __construct(string $operationParam = "__operation")
	{
		parent::__construct($operationParam);
	}

	public function generatePictureURL(string $albumId, string $pictureId): string
	{
		return $this->generateAddPictureURL($albumId)."&amp;PICTURE_ID=".rawurlencode($pictureId);
	}

	public function generateAddPictureURL(string $albumId): string
	{
		return "picture.php?ALBUM_ID=".rawurlencode($albumId);
	}

	public function generateAddMultiplePicturesURL(string $albumId): string
	{
		return "picturesuploader.php?ALBUM_ID=".rawurlencode($albumId);
	}

	public function generateMovePictureLeftURL(int $id, string $albumId, string $pictureId): string
	{
		return $this->generatePictureURL($albumId, $pictureId)."&amp;".$this->operationParam."=moveleft_picture&amp;__id=".$id;
	}

	public function generateMovePictureRightURL(int $id, string $albumId, string $pictureId): string
	{
		return $this->generatePictureURL($albumId, $pictureId)."&amp;".$this->operationParam."=moveright_picture&amp;__id=".$id;
	}

	public function generateSetAsThumbnailURL(int $id, string $albumId, string $pictureId): string
	{
		return $this->generatePictureURL($albumId, $pictureId)."&amp;".$this->operationParam."=setasthumbnail_picture&amp;__id=".$id;
	}

	public function generateRemovePictureURL(int $id, string $albumId, string $pictureId): string
	{
		return $this->generatePictureURL($albumId, $pictureId)."&amp;".$this->operationParam."=remove_picture&amp;__id=".$id;
	}

	public function generateAlbumFormURL(?string $albumId): ?string
	{
		$result = "album.php";

		if($albumId !== null)
			$result .= "?ALBUM_ID=".rawurlencode($albumId);

		return $result;
	}

	public function generatePicturesUploaderFormURL(string $albumId): string
	{
		return "picturesuploader.php";
	}
}
?>
